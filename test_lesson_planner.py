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
    print(" TESTING LESSON PLANNER (CURRICULUM & SYLLABUS)   ")
    print("==================================================")

    routes = [
        ("/school/lesson-plans/dashboard", "Lesson Planner Dashboard Master"),
        ("/school/lesson-plans/plans", "Lesson Plans Ledger & Filter"),
        ("/school/lesson-plans/plans/create", "New Lesson Plan Form"),
        ("/school/lesson-plans/plans/1", "Plan Detail View & Topics"),
        ("/school/lesson-plans/plans/1/edit", "Edit Lesson Plan Form"),
        ("/school/lesson-plans/guide", "Operational Guide & Pedagogy Matrix"),
        ("/school/lesson-plans/review", "HOD Department Review Queue"),
        ("/school/lesson-plans/approvals", "Principal Final Approvals Hub"),
        ("/school/lesson-plans/coverage", "Topic Coverage Ledger"),
        ("/school/lesson-plans/coverage/1", "Mark Topic Coverage Form"),
        ("/school/lesson-plans/reports", "Syllabus Completion & Chronic Non-Coverage Reports"),
        ("/school/lesson-plans/settings", "Planner Settings & HOD Mapping"),
    ]

    cookie_processor = urllib.request.HTTPCookieProcessor()
    opener = urllib.request.build_opener(cookie_processor)

    passed_count = 0
    total_count = len(routes)
    csrf_token = None

    print("\n----------------- Testing GET Routes -----------------")
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
    post_total = 8

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/lesson-plans/plans/create", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    # POST 1: Create New Lesson Plan
    created_plan_id = None
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'title': 'Optics & Ray Diagrams - Advanced Physics',
            'staff_id': '1',
            'section_id': '1',
            'week_start_date': '2026-11-16',
            'week_end_date': '2026-11-20',
            'planned_periods': '5',
            'learning_objectives': 'Understand Snell\'s law, refraction through prisms, and convex lens image formation.',
            'chapter_names': 'Chapter 10: Light - Reflection and Refraction',
            'teaching_methods[]': ['Lecture & Board Work', 'Lab Demonstration'],
            'teaching_aids[]': ['Smart Board / PPT', 'Lab Equipment / Kits'],
            'resources_required': 'Optical bench, convex lenses (f=15cm, 20cm), screen, laser pointer',
            'adhoc_topics': 'Brief introduction to fiber optics and total internal reflection',
            'homework_planned': 'Exercise 10.2 numericals 1 through 8',
            'topics[0][topic_name]': 'Snell\'s Law and Refractive Index',
            'topics[0][subtopic_name]': 'Derivation of n = sin(i)/sin(r)',
            'topics[0][periods_allocated]': '2',
            'topics[1][topic_name]': 'Convex Lens Image Formation',
            'topics[1][subtopic_name]': 'Ray diagrams for all 6 object positions',
            'topics[1][periods_allocated]': '3',
        }, doseq=True).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/lesson-plans/plans", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/plans (Create New Lesson Plan with Topics)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/plans")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/plans: {e}")

    # Fetch newly created plan ID or use 4
    target_plan_id = 4

    # POST 2: Submit Plan for Review
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/plans/{target_plan_id}/submit", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print(f"[OK] POST /school/lesson-plans/plans/{target_plan_id}/submit (Submit Plan for Review)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/plans/{target_plan_id}/submit")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/plans/{target_plan_id}/submit: {e}")

    # POST 3: HOD Endorse Action
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'action': 'approve',
            'review_feedback': 'Pedagogy and lab allocations align with CBSE senior physics curriculum. Endorsed.'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/review/2", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/review/2 (HOD Endorse Action)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/review/2")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/review/2: {e}")

    # POST 4: Principal Approve Action
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'action': 'approve',
            'review_feedback': 'Fully approved. Ensure lab safety protocol during lens demonstrations.'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/approvals/3", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/approvals/3 (Principal Final Approval)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/approvals/3")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/approvals/3: {e}")

    # POST 5: Clone Plan to Next Week
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/plans/1/clone", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/plans/1/clone (Clone Plan to Next Week)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/plans/1/clone")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/plans/1/clone: {e}")

    # POST 6: Update Topic Coverage Progress
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'topics[1][coverage_status]': 'Covered',
            'topics[1][covered_on]': '2026-11-09',
            'topics[1][coverage_reason]': 'Completed lecture and practice exercises successfully.',
            'topics[2][coverage_status]': 'Covered',
            'topics[2][covered_on]': '2026-11-10',
            'topics[2][coverage_reason]': 'All elimination method cases covered.'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/coverage/1", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/coverage/1 (Update Topic Coverage Progress)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/coverage/1")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/coverage/1: {e}")

    # POST 7: Update Lesson Planner Settings
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'hod_step_enabled': '1',
            'allow_edit_after_submit': '0',
            'reminder_enabled': '1',
            'reminder_day': 'Friday',
            'reminder_time': '15:00',
            'submission_deadline_day': 'Sunday',
            'submission_deadline_time': '20:00'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/settings", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/settings (Update Planner Settings)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/settings")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/settings: {e}")

    # POST 8: Assign Department Head
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'department': 'Social Studies',
            'head_staff_id': '1'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/lesson-plans/settings/department-heads", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/lesson-plans/settings/department-heads (Assign Department Head)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/lesson-plans/settings/department-heads")
    except Exception as e:
        print(f"[ERROR] POST /school/lesson-plans/settings/department-heads: {e}")

    print(f"\nPOST Actions: {post_passed}/{post_total} PASSED")

    total_tests = total_count + post_total
    total_passed = passed_count + post_passed
    print("==================================================")
    print(f" RESULT: {total_passed}/{total_tests} Tests Passed ({(total_passed/total_tests)*100:.1f}%)")
    print("==================================================")

    if total_passed == total_tests:
        print("\n>>> LESSON PLANNER MODULE: 100% SUCCESS <<<")
        sys.exit(0)
    else:
        print("\n>>> LESSON PLANNER MODULE: ISSUES FOUND <<<")
        sys.exit(1)

if __name__ == "__main__":
    main()

