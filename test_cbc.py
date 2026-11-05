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
    print(" TESTING CBC ACADEMICS (COMPETENCY-BASED CURRICULUM) ")
    print("==================================================")

    # 1. Test GET routes
    routes = [
        ("/school/cbc", "CBC Root Redirect / Dashboard"),
        ("/school/cbc/dashboard", "CBC Dashboard & KPI"),
        ("/school/cbc/strands", "Curriculum Strands & Outcomes"),
        ("/school/cbc/assessments", "CBC Assessments & Rubric Scoring"),
        ("/school/cbc/competencies", "Core Competencies Master"),
        ("/school/cbc/pathways", "Senior Pathways & Tracks"),
        ("/school/cbc/cbcreports", "CBC Reports & Analytics"),
        ("/school/cbc/reports", "CBC Reports Alternative Route"),
        ("/school/cbc/reports/student/1", "Student Printable Holistic Profile"),
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
        # fetch from dashboard
        req = urllib.request.Request(BASE_URL + "/school/cbc/strands", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    # POST 1: Add Strand
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'subject_id': 1,
            'name': 'Algebra & Mathematical Logic',
            'code': 'ALG-LOGIC',
            'description': 'Advanced algebraic reasoning and logic structures',
            'sort_order': 5
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/cbc/strands", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/cbc/strands (Add Strand)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/cbc/strands")
    except Exception as e:
        print(f"[ERROR] POST /school/cbc/strands: {e}")

    # POST 2: Add Learning Outcome
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'cbc_strand_id': 1,
            'name': 'Demonstrates linear equation solving with geometric proofs',
            'code': 'LO-ALG-05',
            'description': 'Can model and solve linear word problems with proofs'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/cbc/outcomes", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/cbc/outcomes (Add Learning Outcome)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/cbc/outcomes")
    except Exception as e:
        print(f"[ERROR] POST /school/cbc/outcomes: {e}")

    # POST 3: Create Assessment
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'class_id': 1,
            'section_id': 1,
            'subject_id': 1,
            'title': 'Continuous Formative Review - Test CBC',
            'assessment_date': '2026-11-05',
            'type': 'Formative',
            'description': 'Automated test assessment'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/cbc/assessments", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/cbc/assessments (Create Assessment)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/cbc/assessments")
    except Exception as e:
        print(f"[ERROR] POST /school/cbc/assessments: {e}")

    # POST 4: Add Competency
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'name': 'Environmental Stewardship & Sustainability',
            'category': 'Citizenship & Eco-Literacy',
            'description': 'Demonstrates active responsibility towards environmental preservation',
            'indicators': "Practices waste segregation\nLeads green campus initiatives\nConserves energy"
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/cbc/competencies", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/cbc/competencies (Add Competency)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/cbc/competencies")
    except Exception as e:
        print(f"[ERROR] POST /school/cbc/competencies: {e}")

    # POST 5: Add Pathway
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'name': 'Applied Computing & Robotics',
            'icon': 'fa-solid fa-robot',
            'description': 'AI, Machine Learning, and embedded systems pathway'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/cbc/pathways", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/cbc/pathways (Add Pathway)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/cbc/pathways")
    except Exception as e:
        print(f"[ERROR] POST /school/cbc/pathways: {e}")

    print(f"\nPOST Actions: {post_passed}/{post_total} PASSED")

    if passed_count == total_count and post_passed == post_total:
        print("\nALL CBC ACADEMICS TESTS PASSED SUCCESSFULLY! (100% OK)")
        return 0
    else:
        print("\nSOME TESTS FAILED.")
        return 1

if __name__ == "__main__":
    sys.exit(main())
