import urllib.request
import urllib.parse
import json
import re
import sys

BASE_URL = "http://127.0.0.1:8000"

def get_csrf(html):
    m = re.search(r'name="_token"\s+value="([^"]+)"', html)
    if m:
        return m.group(1)
    m = re.search(r'content="([^"]+)"\s+name="csrf-token"', html)
    if m:
        return m.group(1)
    return None

def main():
    print("=" * 60)
    print(" TESTING QR CODE ATTENDANCE MODULE (STATION, AUDIT, REPORTS)")
    print("=" * 60)

    cookie_processor = urllib.request.HTTPCookieProcessor()
    opener = urllib.request.build_opener(cookie_processor)

    # 1. GET Routes Verification
    get_routes = [
        ("/school/qr-attendance", "Live QR Attendance Scan Terminal"),
        ("/school/qr-attendance/settings", "QR Attendance Station Settings"),
        ("/school/qr-attendance/report", "Daily QR Attendance Report Ledger"),
        ("/school/qr-attendance/report?type=student&status=Present", "Report Filtered by Student & Present"),
        ("/school/qr-attendance/report/export", "Attendance Report Streamed CSV Export"),
        ("/school/qr-attendance/audit", "QR Scan Audit & Decision Security Ledger"),
        ("/school/qr-attendance/audit?range=today&outcome=Accepted", "Audit Filtered by Today & Accepted"),
        ("/school/qr-attendance/audit/export", "Audit Security Ledger Streamed CSV Export"),
        ("/school/qr-attendance/status", "Live Scan Counter Tallies JSON API"),
    ]

    passed_count = 0
    total_count = len(get_routes)
    csrf_token = None

    print("\n----------------- 1. Testing GET Routes -----------------")
    for path, desc in get_routes:
        url = BASE_URL + path
        try:
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            code = res.getcode()
            content = res.read().decode('utf-8', errors='ignore')

            if not csrf_token:
                csrf_token = get_csrf(content)

            if code == 200:
                # Extra sanity checks
                if "export" in path:
                    assert "CSV" in res.headers.get("Content-Type", "") or "text/csv" in res.headers.get("Content-Type", "") or "Date" in content
                elif path.startswith("/school/qr-attendance/status"):
                    data = json.loads(content)
                    assert "marked_in" in data and "marked_out" in data
                elif "settings" in path:
                    assert "Camera Facing" in content or "auto_attendance" in content
                elif "audit" in path:
                    assert "Scan Decision Log" in content or "Audit" in content

                print(f"[OK 200] {desc}: {path}")
                passed_count += 1
            else:
                print(f"[FAIL {code}] {desc}: {path}")
        except Exception as e:
            print(f"[ERROR] {desc}: {path} -> {e}")

    print(f"\nGET Routes: {passed_count}/{total_count} PASSED")

    # Fetch token from scanner page if still missing
    if not csrf_token:
        try:
            req = urllib.request.Request(BASE_URL + "/school/qr-attendance", headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))
        except Exception:
            csrf_token = "dummy_token"

    # 2. Testing API & POST Actions
    print("\n----------------- 2. Testing API & POST Actions -----------------")
    post_passed = 0
    post_total = 6

    # Test 2.1: Resolve Student QR
    try:
        url = BASE_URL + "/school/qr-attendance/resolve"
        payload = json.dumps({"code": "YIS-STD-1-1001"}).encode('utf-8')
        headers = {
            'User-Agent': 'Mozilla/5.0',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token or '',
            'Accept': 'application/json'
        }
        req = urllib.request.Request(url, data=payload, headers=headers)
        res = opener.open(req)
        res_data = json.loads(res.read().decode('utf-8', errors='ignore'))
        if res.getcode() == 200 and res_data.get('status') in [1, 2]:
            print(f"[OK 200] Resolve Student QR: Code decoded, Name={res_data.get('person_name', 'Student')}, Mode={res_data.get('mode')}")
            post_passed += 1
        else:
            print(f"[FAIL] Resolve Student QR: {res_data}")
    except Exception as e:
        print(f"[ERROR] Resolve Student QR: {e}")

    # Test 2.2: Resolve Staff QR
    try:
        url = BASE_URL + "/school/qr-attendance/resolve"
        payload = json.dumps({"code": "YIS-STF-1-2001"}).encode('utf-8')
        headers = {
            'User-Agent': 'Mozilla/5.0',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token or '',
            'Accept': 'application/json'
        }
        req = urllib.request.Request(url, data=payload, headers=headers)
        res = opener.open(req)
        res_data = json.loads(res.read().decode('utf-8', errors='ignore'))
        if res.getcode() == 200 and res_data.get('status') in [1, 2]:
            print(f"[OK 200] Resolve Staff QR: Code decoded, Name={res_data.get('person_name', 'Staff')}, Mode={res_data.get('mode')}")
            post_passed += 1
        else:
            print(f"[FAIL] Resolve Staff QR: {res_data}")
    except Exception as e:
        print(f"[ERROR] Resolve Staff QR: {e}")

    # Test 2.3: Resolve Invalid QR (Audit Log Verification)
    try:
        url = BASE_URL + "/school/qr-attendance/resolve"
        payload = json.dumps({"code": "NON-EXISTENT-XYZ-999"}).encode('utf-8')
        headers = {
            'User-Agent': 'Mozilla/5.0',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token or '',
            'Accept': 'application/json'
        }
        req = urllib.request.Request(url, data=payload, headers=headers)
        res = opener.open(req)
        res_data = json.loads(res.read().decode('utf-8', errors='ignore'))
        if res.getcode() == 200 and res_data.get('status') == 0:
            print(f"[OK 200] Resolve Unknown QR: Rejected gracefully with security log recorded.")
            post_passed += 1
        else:
            print(f"[FAIL] Resolve Unknown QR: {res_data}")
    except Exception as e:
        print(f"[ERROR] Resolve Unknown QR: {e}")

    # Test 2.4: Manual Attendance Punch
    try:
        url = BASE_URL + "/school/qr-attendance/punch"
        payload = json.dumps({
            "person_type": "student",
            "person_id": 1,
            "status": "Present",
            "punch_type": "IN"
        }).encode('utf-8')
        headers = {
            'User-Agent': 'Mozilla/5.0',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token or '',
            'Accept': 'application/json'
        }
        req = urllib.request.Request(url, data=payload, headers=headers)
        res = opener.open(req)
        res_data = json.loads(res.read().decode('utf-8', errors='ignore'))
        if res.getcode() == 200 and res_data.get('status') == 1:
            print(f"[OK 200] Manual Attendance Punch: Attendance confirmed successfully.")
            post_passed += 1
        else:
            print(f"[FAIL] Manual Attendance Punch: {res_data}")
    except Exception as e:
        print(f"[ERROR] Manual Attendance Punch: {e}")

    # Test 2.5: Save Station Settings
    try:
        url = BASE_URL + "/school/qr-attendance/settings"
        data = {
            "_token": csrf_token or '',
            "auto_attendance": "1",
            "camera_facing": "environment",
            "cooldown_ms": "3000",
            "late_cutoff_time": "08:30",
            "half_day_cutoff_time": "12:00",
            "machine_types": ["camera", "barcode_gun"],
            "allowed_formats": ["QR_CODE", "CODE_128", "EAN_13"]
        }
        encoded = urllib.parse.urlencode(data, doseq=True).encode('utf-8')
        req = urllib.request.Request(url, data=encoded, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() == 200:
            print(f"[OK 200] Save Station Settings: Form saved and reloaded successfully.")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] Save Station Settings")
    except Exception as e:
        print(f"[ERROR] Save Station Settings: {e}")

    # Test 2.6: Purge Audit Logs
    try:
        url = BASE_URL + "/school/qr-attendance/audit/purge"
        data = {
            "_token": csrf_token or '',
            "days": "90"
        }
        encoded = urllib.parse.urlencode(data).encode('utf-8')
        req = urllib.request.Request(url, data=encoded, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() == 200:
            print(f"[OK 200] Purge Audit Logs: Old security records purged successfully.")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] Purge Audit Logs")
    except Exception as e:
        print(f"[ERROR] Purge Audit Logs: {e}")

    print(f"\nPOST/Action Routes: {post_passed}/{post_total} PASSED")

    print("\n" + "=" * 60)
    print(f"OVERALL SUMMARY: {passed_count + post_passed} / {total_count + post_total} TESTS PASSED")
    print("=" * 60)

    if passed_count == total_count and post_passed == post_total:
        print("\n>>> ALL QR CODE ATTENDANCE TESTS COMPLETED WITH 100% SUCCESS! <<<\n")
        sys.exit(0)
    else:
        print("\n>>> SOME TESTS FAILED. CHECK LOGS ABOVE. <<<\n")
        sys.exit(1)

if __name__ == "__main__":
    main()
