import urllib.request
import urllib.parse
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
    print("==================================================")
    print(" TESTING PTM MEETINGS (PARENT-TEACHER CONFERENCES)")
    print("==================================================")

    routes = [
        ("/school/ptm/dashboard", "PTM Dashboard Master"),
        ("/school/ptm/meetings", "Schedule Meetings Ledger"),
        ("/school/ptm/meetings/create", "Schedule PTM Form"),
        ("/school/ptm/meetings/1", "PTM Details & Guardian Roster"),
        ("/school/ptm/meetings/1/edit", "Edit PTM Form"),
        ("/school/ptm/record", "PTM Attendance & Remarks Hub"),
        ("/school/ptm/meetings/1/record", "Student Remarks & Star Rating Matrix"),
        ("/school/ptm/followups", "PTM Follow-ups & Remedial Log"),
        ("/school/ptm/reports", "PTM Reports Master"),
        ("/school/ptm/reports?meeting_id=1", "PTM Detailed Meeting Report"),
        ("/school/ptm/guide", "PTM Operational Guide & Role Matrix"),
    ]

    cookie_processor = urllib.request.HTTPCookieProcessor()
    opener = urllib.request.build_opener(cookie_processor)

    passed_count = 0
    total_count = len(routes)
    csrf_token = None

    for path, desc in routes:
        url = BASE_URL + path
        try:
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            code = res.getcode()
            html = res.read().decode('utf-8', errors='ignore')
            if not csrf_token:
                csrf_token = get_csrf(html)
            
            if code == 200:
                print(f"[OK 200] {desc}: {path}")
                passed_count += 1
            else:
                print(f"[FAIL {code}] {desc}: {path}")
        except Exception as e:
            print(f"[ERROR] {desc}: {path} -> {e}")

    print(f"\nGET Routes: {passed_count}/{total_count} PASSED")

    # 2. Test POST submissions
    print("\n----------------- Testing POST Actions -----------------")
    post_passed = 0
    post_total = 5

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/ptm/meetings/create", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    # POST 1: Schedule PTM Meeting
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'title': 'Mid-Term STEM & Robotics Milestone Conference',
            'meeting_date': '2026-12-10',
            'start_time': '09:30',
            'end_time': '12:30',
            'mode': 'in_person',
            'venue': 'Robotics Lab & Innovation Studio',
            'scope': 'whole_school',
            'description': 'Discussion on project-based learning and inter-school olympiad entries.',
            'generate_roster': '1'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/ptm/meetings", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/ptm/meetings (Schedule Meeting with Auto Roster)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/ptm/meetings")
    except Exception as e:
        print(f"[ERROR] POST /school/ptm/meetings: {e}")

    # POST 2: Broadcast Invitations / Mark Notified
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/ptm/meetings/1/notify", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/ptm/meetings/1/notify (Broadcast Invitations)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/ptm/meetings/1/notify")
    except Exception as e:
        print(f"[ERROR] POST /school/ptm/meetings/1/notify: {e}")

    # POST 3: Save Attendance & Remarks
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'rows[1][attendance_status]': 'Present',
            'rows[1][performance_remark]': 'Exceptional logical thinking and participation.',
            'rows[1][attendance_remark]': 'Consistently on time.',
            'rows[1][behaviour_remark]': 'Courteous and proactive.',
            'rows[1][overall_rating]': '5',
            'rows[1][visible_to_parent]': '1'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/ptm/meetings/1/record", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/ptm/meetings/1/record (Save Attendance & Remarks)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/ptm/meetings/1/record")
    except Exception as e:
        print(f"[ERROR] POST /school/ptm/meetings/1/record: {e}")

    # POST 4: Create Follow-up
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'ptm_meeting_id': 1,
            'student_id': 1,
            'action_plan': 'Assign additional scientific reading material for term exam.',
            'assigned_to': 'Science Faculty Lead',
            'due_date': '2026-12-20',
            'status': 'Open'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/ptm/followups", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/ptm/followups (Create Follow-up Action Item)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/ptm/followups")
    except Exception as e:
        print(f"[ERROR] POST /school/ptm/followups: {e}")

    # POST 5: Update Follow-up
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            '_method': 'PUT',
            'status': 'Completed'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/ptm/followups/1", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] PUT /school/ptm/followups/1 (Update Follow-up Status)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] PUT /school/ptm/followups/1")
    except Exception as e:
        print(f"[ERROR] PUT /school/ptm/followups/1: {e}")

    print(f"\nPOST Actions: {post_passed}/{post_total} PASSED")

    total_score = (passed_count + post_passed)
    max_score = (total_count + post_total)
    print(f"\nOVERALL RESULT: {total_score}/{max_score} tests passed.")

    if total_score == max_score:
        print("ALL PTM MEETINGS TESTS COMPLETED SUCCESSFULLY! (100% SUCCESS)")
        sys.exit(0)
    else:
        print("SOME TESTS FAILED! Check outputs above.")
        sys.exit(1)

if __name__ == '__main__':
    main()
