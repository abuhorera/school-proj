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
    print(" TESTING OSM MODULE (ON-SCREEN MARKING DIGITAL)   ")
    print("==================================================")

    routes = [
        ("/school/osm/dashboard", "OSM Dashboard Master"),
        ("/school/osm/sessions", "OSM Sessions Ledger"),
        ("/school/osm/sessions/create", "New Evaluation Session Form"),
        ("/school/osm/sessions/1", "Session Workbench Detail"),
        ("/school/osm/sessions/1/edit", "Edit Session Form"),
        ("/school/osm/evaluate", "Evaluator Workbench Queue"),
        ("/school/osm/evaluate/1", "On-Screen Marking Canvas"),
        ("/school/osm/moderation", "OSM Moderation Console"),
        ("/school/osm/moderation?session_id=1", "Moderation with Session Filter"),
        ("/school/osm/reports", "OSM Reports Hub"),
        ("/school/osm/reports?session_id=1", "Reports with Session Filter"),
        ("/school/osm/reports/analytics/1", "Session Class Analytics"),
        ("/school/osm/reports/evaluator/1", "Evaluator Turnaround Audit"),
        ("/school/osm/guide", "OSM Operational Guide"),
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
        req = urllib.request.Request(BASE_URL + "/school/osm/sessions/create", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    # POST 1: Create New Evaluation Session
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'title': 'Mid-Term Organic Chemistry & Molecular Architecture',
            'school_class_id': '1',
            'section_id': '1',
            'subject_id': '1',
            'total_marks': '50',
            'evaluation_deadline': '2026-11-30',
            'moderation_level': 'sample_20',
            'sync_to_report_card': '1',
            'publish_to_parent': '1',
            'anonymous_mode': '1',
            'allow_annotations': '1',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/osm/sessions", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/sessions (Create New Evaluation Session)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/sessions")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/sessions: {e}")

    # POST 2: Save Question Paper Structure
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'questions[0][question_number]': '1',
            'questions[0][question_label]': 'Q1',
            'questions[0][max_marks]': '15',
            'questions[0][is_compulsory]': '1',
            'questions[0][question_text]': 'Explain SN1 vs SN2 reaction mechanisms with stereochemical proofs.',
            'questions[1][question_number]': '2',
            'questions[1][question_label]': 'Q2',
            'questions[1][max_marks]': '20',
            'questions[1][is_compulsory]': '1',
            'questions[1][question_text]': 'Synthesize aspirin starting from benzene ring.',
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/sessions/1/questions", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/sessions/1/questions (Save Question Paper Structure)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/sessions/1/questions")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/sessions/1/questions: {e}")

    # POST 3: Upload Answer Sheets & Auto-Map
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/sessions/1/upload-sheets", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/sessions/1/upload-sheets (Bulk Upload & Map Sheets)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/sessions/1/upload-sheets")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/sessions/1/upload-sheets: {e}")

    # POST 4: Assign Evaluators
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'staff_ids[]': ['1'],
        }, doseq=True).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/sessions/1/assign-evaluators", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/sessions/1/assign-evaluators (Assign Faculty Evaluators)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/sessions/1/assign-evaluators")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/sessions/1/assign-evaluators: {e}")

    # POST 5: Score Single Question on Canvas
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'question_id': '1',
            'marks_awarded': '8.5',
            'comment': 'Good derivation and clear transition state diagrams.'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/evaluate/1/score-question", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/evaluate/1/score-question (Score Single Question)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/evaluate/1/score-question")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/evaluate/1/score-question: {e}")

    # POST 6: Save Annotation Strokes/Stamps
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'page_number': '1',
            'tool_type': 'tick',
            'x': '150',
            'y': '250',
            'data_json': '{"stamp":"tick","color":"#16a34a"}'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/evaluate/1/annotations", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/evaluate/1/annotations (Save Canvas Annotation Stamp)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/evaluate/1/annotations")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/evaluate/1/annotations: {e}")

    # POST 7: Finish Paper Evaluation
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'elapsed_seconds': '240',
            'evaluator_notes': 'Complete paper evaluated thoroughly with annotations.'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/evaluate/1/finish", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/evaluate/1/finish (Finish Paper Evaluation)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/evaluate/1/finish")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/evaluate/1/finish: {e}")

    # POST 8: Head Examiner Moderation Decision
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'moderated_score': '17.5',
            'moderator_notes': 'Endorsed. Additional 1 mark granted for Q1 alternative solution step.'
        }).encode('utf-8')
        req = urllib.request.Request(f"{BASE_URL}/school/osm/moderation/1", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/osm/moderation/1 (Head Examiner Moderation Decision)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/osm/moderation/1")
    except Exception as e:
        print(f"[ERROR] POST /school/osm/moderation/1: {e}")

    print(f"\nPOST Actions: {post_passed}/{post_total} PASSED")

    total_tests = total_count + post_total
    total_passed = passed_count + post_passed
    print("==================================================")
    print(f" RESULT: {total_passed}/{total_tests} Tests Passed ({(total_passed/total_tests)*100:.1f}%)")
    print("==================================================")

    if total_passed == total_tests:
        print("\n>>> OSM MODULE: 100% SUCCESS <<<")
        sys.exit(0)
    else:
        print("\n>>> OSM MODULE: ISSUES FOUND <<<")
        sys.exit(1)

if __name__ == "__main__":
    main()

