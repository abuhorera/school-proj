import urllib.request
import urllib.parse
import re
import sys
import sqlite3

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
    print("=" * 70)
    print(" TESTING COMPLIANCE & GOVERNANCE MODULE (UDISE+, VAULT, VALIDATOR, SARAS)")
    print("=" * 70)

    cookie_processor = urllib.request.HTTPCookieProcessor()
    opener = urllib.request.build_opener(cookie_processor)

    # 1. GET Routes Verification
    get_routes = [
        ("/school/compliance", "Compliance Overview Hub"),
        ("/school/compliance/profile", "School Compliance Profile Form"),
        ("/school/compliance/packs", "Compliance Standard Packs Gallery"),
        ("/school/compliance/fields", "Compliance Field Settings"),
        ("/school/compliance/documents", "Document Vault Ledger"),
        ("/school/compliance/documents?owner_type=school", "Document Vault Filtered by School"),
        ("/school/compliance/checklist", "Statutory Compliance Checklist"),
        ("/school/compliance/records", "Data Records Bulk Grid (Student)"),
        ("/school/compliance/records?owner_type=staff", "Data Records Bulk Grid (Staff)"),
        ("/school/compliance/validator", "Data Validator & Quality Findings"),
        ("/school/compliance/validator/export", "Data Validator Worklist Streamed CSV"),
        ("/school/compliance/reports", "Government Reports Hub (UDISE+)"),
        ("/school/compliance/reports/student/export", "UDISE+ Student Master CSV Stream"),
        ("/school/compliance/reports/teacher/export", "UDISE+ Teacher Master CSV Stream"),
        ("/school/compliance/reports/school/export", "UDISE+ School Profile CSV Stream"),
        ("/school/compliance/inspections", "Inspection Checklists & Affiliation Renewal"),
        ("/school/compliance/calendar", "Compliance Calendar Statutory Deadlines"),
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
                print(f"[OK] {desc}: {path}")
                passed_count += 1
            else:
                print(f"[FAIL {code}] {desc}: {path}")
        except Exception as e:
            print(f"[ERROR] {desc}: {path} -> {e}")

    print(f"\nGET Routes: {passed_count}/{total_count} PASSED")

    # 2. Test Form Mutations & Actions
    print("\n----------------- 2. Testing Mutation Actions -----------------")
    mutations_passed = 0
    mutations_total = 8

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/compliance/profile", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    created_doc_id = None

    # ACTION 1: Update School Profile
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            '_method': 'PUT',
            'udise_code': '07010200345',
            'recognition_no': 'EDN/REC/2018/8921-UPDATED',
            'recognition_date': '2018-04-12',
            'management_type': 'Private Unaided',
            'school_category': 'Higher Secondary (I–XII)',
            'board': 'CBSE',
            'affiliation_no': 'CBSE/AFF/2130982',
            'valid_upto': '2028-03-31',
            'campus_area_sqm': '12500',
            'built_up_area_sqm': '6400',
            'playground_available': '1',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/profile", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] PUT /school/compliance/profile (Updated Institutional Profile)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] PUT /school/compliance/profile")
    except Exception as e:
        print(f"[ERROR] Update Profile: {e}")

    # ACTION 2: Install Compliance Pack
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'pack_key': 'nemis',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/packs/install", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/compliance/packs/install (Installed Pack: nemis)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/compliance/packs/install")
    except Exception as e:
        print(f"[ERROR] Install Pack: {e}")

    # ACTION 3: Save Field Settings
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'visible[apaar_id]': '1',
            'required[apaar_id]': '1',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/fields", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/compliance/fields (Saved Field Visibility Settings)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/compliance/fields")
    except Exception as e:
        print(f"[ERROR] Save Fields: {e}")

    # ACTION 4: Add Document to Vault
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'owner_type': 'school',
            'category': 'Transport Fitness',
            'title': 'Automated Test: School Bus Fitness Certificate 2026',
            'document_number': 'RTO/DL/BUS/492',
            'issue_date': '2026-01-10',
            'expiry_date': '2027-01-10',
            'notes': 'All 8 school buses inspected by RTO inspector.',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/documents", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            conn = sqlite3.connect('database/database.sqlite')
            cursor = conn.cursor()
            cursor.execute("SELECT id FROM compliance_documents WHERE title = 'Automated Test: School Bus Fitness Certificate 2026' ORDER BY id DESC LIMIT 1")
            row = cursor.fetchone()
            if row:
                created_doc_id = row[0]
            conn.close()
            print(f"[OK] POST /school/compliance/documents (Added Document #{created_doc_id} to Vault)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/compliance/documents")
    except Exception as e:
        print(f"[ERROR] Add Document: {e}")

    # ACTION 5: Run Data Validator
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'owner_type': 'student',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/validator/run", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/compliance/validator/run (Executed Pre-Flight Validation Engine)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/compliance/validator/run")
    except Exception as e:
        print(f"[ERROR] Run Validator: {e}")

    # ACTION 6: Waive Validation Finding
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'result_id': '1',
            'reason': 'Aadhaar enrollment slip available; official card in dispatch',
            'expires_on': '2027-05-30',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/validator/waive", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/compliance/validator/waive (Accepted Administrative Waive)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/compliance/validator/waive")
    except Exception as e:
        print(f"[ERROR] Waive Finding: {e}")

    # ACTION 7: Save Inspection Progress
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'checklist': 'cbse_renewal',
            'items[building_safety][status]': 'Done',
            'items[building_safety][note]': 'PWD certificate verified valid till 2028',
            'items[composite_lab][status]': 'Done',
            'items[composite_lab][note]': 'All apparatus verified compliant with CBSE bylaws',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/compliance/inspections", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/compliance/inspections (Updated Inspection Audit Items)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/compliance/inspections")
    except Exception as e:
        print(f"[ERROR] Save Inspection: {e}")

    # ACTION 8: Delete Document
    if created_doc_id:
        try:
            data = urllib.parse.urlencode({'_token': csrf_token, '_method': 'DELETE'}).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/compliance/documents/{created_doc_id}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] DELETE /school/compliance/documents/{created_doc_id} (Cleaned Up Test Document)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] DELETE /school/compliance/documents/{created_doc_id}")
        except Exception as e:
            print(f"[ERROR] Delete Document: {e}")
    else:
        print("[SKIP] Delete Document (No ID)")

    print(f"\nMutations: {mutations_passed}/{mutations_total} PASSED")

    print("\n" + "=" * 70)
    total_tests = total_count + mutations_total
    all_passed = passed_count + mutations_passed
    print(f"FINAL RESULT: {all_passed}/{total_tests} TESTS PASSED ({(all_passed/total_tests)*100:.1f}%)")
    print("=" * 70)

    if all_passed == total_tests:
        sys.exit(0)
    else:
        sys.exit(1)

if __name__ == "__main__":
    main()
