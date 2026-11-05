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
    print(" TESTING CLASSROOM ASSESSMENT MODULE (QUIZZES, SITTINGS, MARKS & REPORTS)")
    print("=" * 70)

    cookie_processor = urllib.request.HTTPCookieProcessor()
    opener = urllib.request.build_opener(cookie_processor)

    # 1. GET Routes Verification
    get_routes = [
        ("/school/assessment/dashboard", "Assessment Executive Dashboard"),
        ("/school/assessment/assessments", "Assessments Master Ledger"),
        ("/school/assessment/assessments?type=Quiz", "Assessments Filtered by Type (Quiz)"),
        ("/school/assessment/assessments?class_id=1", "Assessments Filtered by Class (Class 1)"),
        ("/school/assessment/assessments/create", "Create New Assessment Wizard"),
        ("/school/assessment/assessments/1", "Assessment Detail & Sittings Ledger"),
        ("/school/assessment/assessments/1/edit", "Edit Assessment Parameters"),
        ("/school/assessment/occurrences/1/marks", "Student Marks Entry Interactive Grid"),
        ("/school/assessment/occurrences/1/marksheet", "Printable Assessment Marksheet"),
        ("/school/assessment/guide", "Assessment Pedagogical Guide & Workflows"),
        ("/school/assessment/reports", "Assessment Reports & Analytics Hub"),
        ("/school/assessment/reports?type=Test", "Reports Filtered by Type (Test)"),
        ("/school/assessment/reports/ranking", "Class Leaderboard & Ranking List"),
        ("/school/assessment/reports/student", "Candidate Individual Performance Report"),
        ("/school/assessment/reports/student?student_id=1", "Candidate Report for Specific Pupil"),
        ("/school/assessments-hub", "Backward Compatibility Route (/school/assessments-hub)"),
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

    # 2. Test POST / PUT / DELETE Actions
    print("\n----------------- 2. Testing Mutation Actions -----------------")
    mutations_passed = 0
    mutations_total = 5

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/assessment/assessments/create", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    created_id = None
    created_occurrence_id = None

    # POST 1: Create New Assessment
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'title': 'Automated Test: Robotics & AI Concepts',
            'type': 'Quiz',
            'conducted_via': 'Online',
            'school_class_id': '1',
            'section_id': '1',
            'subject_id': '1',
            'total_marks': '25',
            'passing_marks': '10',
            'frequency': 'Weekly',
            'scheduled_date': '2026-11-10',
            'instructions': 'Attempt all 25 multiple choice questions within 30 minutes.',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/assessment/assessments", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            # Query the newly created assessment from sqlite
            conn = sqlite3.connect('database/database.sqlite')
            cursor = conn.cursor()
            cursor.execute("SELECT id FROM classroom_assessments WHERE title = 'Automated Test: Robotics & AI Concepts' ORDER BY id DESC LIMIT 1")
            row = cursor.fetchone()
            if row:
                created_id = row[0]
                cursor.execute("SELECT id FROM assessment_occurrences WHERE assessment_id = ? LIMIT 1", (created_id,))
                occ_row = cursor.fetchone()
                if occ_row:
                    created_occurrence_id = occ_row[0]
            conn.close()

            print(f"[OK] POST /school/assessment/assessments (Created Assessment #{created_id}, Sitting #{created_occurrence_id})")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/assessment/assessments")
    except Exception as e:
        print(f"[ERROR] POST /school/assessment/assessments: {e}")

    # POST 2: Update Assessment Parameters
    if created_id:
        try:
            data = urllib.parse.urlencode({
                '_token': csrf_token,
                '_method': 'PUT',
                'title': 'Automated Test: Advanced Robotics & AI Architectures (Updated)',
                'type': 'Practical',
                'conducted_via': 'Online',
                'school_class_id': '1',
                'section_id': '1',
                'subject_id': '1',
                'total_marks': '30',
                'passing_marks': '12',
                'frequency': 'Monthly',
                'instructions': 'Hands-on practical microcontroller circuit setup.',
            }).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/assessment/assessments/{created_id}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] PUT /school/assessment/assessments/{created_id} (Updated Assessment Parameters)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] PUT /school/assessment/assessments/{created_id}")
        except Exception as e:
            print(f"[ERROR] PUT /school/assessment/assessments/{created_id}: {e}")
    else:
        print("[SKIP] PUT /school/assessment/assessments (No ID)")

    # POST 3: Save Student Marks
    if created_occurrence_id:
        try:
            data = urllib.parse.urlencode({
                '_token': csrf_token,
                'marks[1]': '28.5',
                'marks[2]': '24.0',
                'marks[3]': '',
                'absent[3]': '1',
            }).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/assessment/occurrences/{created_occurrence_id}/marks", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] POST /school/assessment/occurrences/{created_occurrence_id}/marks (Saved Student Marks & Absentees)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] POST /school/assessment/occurrences/{created_occurrence_id}/marks")
        except Exception as e:
            print(f"[ERROR] POST /school/assessment/occurrences/{created_occurrence_id}/marks: {e}")
    else:
        print("[SKIP] POST marks (No Occurrence ID)")

    # POST 4: Publish Sitting Results
    if created_occurrence_id:
        try:
            data = urllib.parse.urlencode({
                '_token': csrf_token,
            }).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/assessment/occurrences/{created_occurrence_id}/publish", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] POST /school/assessment/occurrences/{created_occurrence_id}/publish (Published Results)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] POST /school/assessment/occurrences/{created_occurrence_id}/publish")
        except Exception as e:
            print(f"[ERROR] POST /school/assessment/occurrences/{created_occurrence_id}/publish: {e}")
    else:
        print("[SKIP] POST publish (No Occurrence ID)")

    # POST 5: Delete Assessment
    if created_id:
        try:
            data = urllib.parse.urlencode({
                '_token': csrf_token,
                '_method': 'DELETE',
            }).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/assessment/assessments/{created_id}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] DELETE /school/assessment/assessments/{created_id} (Cleaned Up Assessment)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] DELETE /school/assessment/assessments/{created_id}")
        except Exception as e:
            print(f"[ERROR] DELETE /school/assessment/assessments/{created_id}: {e}")
    else:
        print("[SKIP] DELETE assessment (No ID)")

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

