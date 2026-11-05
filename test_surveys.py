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
    print(" TESTING SURVEYS & FEEDBACK MODULE (SURVEYS, POLLS, TEMPLATES, TRIAGE)")
    print("=" * 70)

    cookie_processor = urllib.request.HTTPCookieProcessor()
    opener = urllib.request.build_opener(cookie_processor)

    # 1. GET Routes Verification
    get_routes = [
        ("/school/survey/dashboard", "Survey Executive Dashboard"),
        ("/school/survey", "All Surveys Master Ledger"),
        ("/school/survey?kind=survey", "Surveys Filtered by Kind (Survey)"),
        ("/school/survey?status=published", "Surveys Filtered by Status (Published)"),
        ("/school/survey/mine", "My Surveys Inbox Feed"),
        ("/school/survey/templates", "Survey Templates Gallery (6 Pro Packs)"),
        ("/school/survey/create", "Create Survey Builder Form"),
        ("/school/survey/create?kind=poll", "Quick Poll Creation Form"),
        ("/school/survey/1", "Survey Details & Questions Overview"),
        ("/school/survey/1/edit", "Edit Survey Parameters Form"),
        ("/school/survey/1/report", "Survey Analytics & Aggregated Report"),
        ("/school/survey/1/report/export", "Streamed CSV Response Export"),
        ("/school/survey/r/wggetcij1889nxt0zhvornqdiv7j6oi1", "Public Respondent Answer Form"),
        ("/school/survey/guide", "Survey Pedagogical & Lifecycle Guide"),
        ("/school/feedback", "Feedback Triage Board"),
        ("/school/feedback?status=New", "Feedback Triage Filtered by New"),
        ("/school/feedback/1", "Feedback Action Ticket Management"),
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
    mutations_total = 9

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/survey/create", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    created_survey_id = None
    created_token = None
    tpl_survey_id = None

    # ACTION 1: Instantiate from Template
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/survey/templates/parent_satisfaction/use", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            conn = sqlite3.connect('database/database.sqlite')
            cursor = conn.cursor()
            cursor.execute("SELECT id FROM surveys WHERE title LIKE '%Parent Satisfaction Survey%' ORDER BY id DESC LIMIT 1")
            row = cursor.fetchone()
            if row:
                tpl_survey_id = row[0]
            conn.close()
            print(f"[OK] POST /school/survey/templates/parent_satisfaction/use (Created Template Survey #{tpl_survey_id})")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/survey/templates/parent_satisfaction/use")
    except Exception as e:
        print(f"[ERROR] Template instantiation: {e}")

    # ACTION 2: Create New Survey
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'title': 'Automated Test: Campus Safety & Cleanliness 2026',
            'description': 'Evaluating campus hygiene, sanitization and security protocols.',
            'kind': 'survey',
            'recurrence_freq': 'Monthly',
            'is_anonymous': '1',
            'audience_type': 'all',
            'questions[0][text]': 'How clean are common restrooms and water dispensers?',
            'questions[0][type]': 'rating_scale',
            'questions[0][min]': '1',
            'questions[0][max]': '5',
            'questions[0][is_required]': '1',
            'questions[1][text]': 'Do security personnel check visitor identity at gate?',
            'questions[1][type]': 'yes_no',
            'questions[1][is_required]': '1',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/survey", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            conn = sqlite3.connect('database/database.sqlite')
            cursor = conn.cursor()
            cursor.execute("SELECT id, access_token FROM surveys WHERE title = 'Automated Test: Campus Safety & Cleanliness 2026' ORDER BY id DESC LIMIT 1")
            row = cursor.fetchone()
            if row:
                created_survey_id = row[0]
                created_token = row[1]
            conn.close()
            print(f"[OK] POST /school/survey (Created Survey #{created_survey_id}, Token {created_token})")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/survey")
    except Exception as e:
        print(f"[ERROR] Survey Creation: {e}")

    # ACTION 3: Update Survey
    if created_survey_id:
        try:
            data = urllib.parse.urlencode({
                '_token': csrf_token,
                '_method': 'PUT',
                'title': 'Automated Test: Campus Safety, Cleanliness & Greenery 2026 (Updated)',
                'description': 'Updated description with additional environmental review.',
                'kind': 'feedback_form',
                'recurrence_freq': 'Termly',
                'audience_type': 'roles',
            }).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/{created_survey_id}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] PUT /school/survey/{created_survey_id} (Updated Survey Parameters)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] PUT /school/survey/{created_survey_id}")
        except Exception as e:
            print(f"[ERROR] Update Survey: {e}")
    else:
        print("[SKIP] Update Survey (No ID)")

    # ACTION 4: Publish Survey
    if created_survey_id:
        try:
            data = urllib.parse.urlencode({'_token': csrf_token}).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/{created_survey_id}/publish", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] POST /school/survey/{created_survey_id}/publish (Published Survey)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] POST /school/survey/{created_survey_id}/publish")
        except Exception as e:
            print(f"[ERROR] Publish Survey: {e}")
    else:
        print("[SKIP] Publish Survey (No ID)")

    # ACTION 5: Submit Respondent Answer
    if created_token:
        try:
            data = urllib.parse.urlencode({
                '_token': csrf_token,
                'respondent_name': 'Automated Tester',
                'answers[1]': '5',
                'answers[2]': 'Yes',
            }).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/r/{created_token}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] POST /school/survey/r/{created_token} (Submitted Respondent Answers)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] POST /school/survey/r/{created_token}")
        except Exception as e:
            print(f"[ERROR] Submit Respondent Answer: {e}")
    else:
        print("[SKIP] Submit Respondent Answer (No Token)")

    # ACTION 6: Publish Survey Report
    if created_survey_id:
        try:
            data = urllib.parse.urlencode({'_token': csrf_token}).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/{created_survey_id}/report/publish", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] POST /school/survey/{created_survey_id}/report/publish (Published Report Analytics)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] POST /school/survey/{created_survey_id}/report/publish")
        except Exception as e:
            print(f"[ERROR] Publish Report: {e}")
    else:
        print("[SKIP] Publish Report (No ID)")

    # ACTION 7: Close Survey
    if created_survey_id:
        try:
            data = urllib.parse.urlencode({'_token': csrf_token}).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/{created_survey_id}/close", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] POST /school/survey/{created_survey_id}/close (Closed Survey Lifecycle)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] POST /school/survey/{created_survey_id}/close")
        except Exception as e:
            print(f"[ERROR] Close Survey: {e}")
    else:
        print("[SKIP] Close Survey (No ID)")

    # ACTION 8: Update Feedback Triage Item
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            '_method': 'PUT',
            'title': 'DEMO — Follow up: transport delays raised by a parent (Resolved)',
            'status': 'Actioned',
            'priority': 'High',
            'tags': 'transport, route-4, resolved',
            'internal_notes': 'Spoke with parent; rerouted morning pickup 15 mins earlier.',
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/feedback/1", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print(f"[OK] PUT /school/feedback/1 (Updated Feedback Triage Ticket)")
            mutations_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] PUT /school/feedback/1")
    except Exception as e:
        print(f"[ERROR] Update Feedback: {e}")

    # ACTION 9: Delete Test Surveys
    if created_survey_id:
        try:
            data = urllib.parse.urlencode({'_token': csrf_token, '_method': 'DELETE'}).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/{created_survey_id}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            res = opener.open(req)
            if res.getcode() in [200, 302]:
                print(f"[OK] DELETE /school/survey/{created_survey_id} (Cleaned Up Test Survey)")
                mutations_passed += 1
            else:
                print(f"[FAIL {res.getcode()}] DELETE /school/survey/{created_survey_id}")
        except Exception as e:
            print(f"[ERROR] Delete Survey: {e}")
    else:
        print("[SKIP] Delete Survey (No ID)")

    # Cleanup template survey if created
    if tpl_survey_id:
        try:
            data = urllib.parse.urlencode({'_token': csrf_token, '_method': 'DELETE'}).encode('utf-8')
            req = urllib.request.Request(f"{BASE_URL}/school/survey/{tpl_survey_id}", data=data, headers={'User-Agent': 'Mozilla/5.0'})
            opener.open(req)
        except Exception:
            pass

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

