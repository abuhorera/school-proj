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
    print(" TESTING ONLINE EXAMINATIONS (CBT & QUESTION BANK) ")
    print("==================================================")

    routes = [
        ("/school/online-exam", "Online Exam Root / Dashboard"),
        ("/school/online-exam/dashboard", "Online Exam Dashboard"),
        ("/school/online-exam/manage-exams", "Manage Online Exams"),
        ("/school/online-exam/builder/1", "Online Exam Paper Builder"),
        ("/school/online-exam/question-bank", "Question Bank Master"),
        ("/school/online-exam/question-bank/create", "Add Question Form"),
        ("/school/online-exam/question-bank/bulk-upload", "Bulk Upload Questions Form"),
        ("/school/online-exam/question-topics", "Chapters & Topics Master"),
        ("/school/online-exam/manage-exams/1/results", "Exam Results Roster"),
        ("/school/online-exam/manage-exams/1/results/1", "Detailed Answer Sheet"),
        ("/school/online-exam/take/1", "Student CBT Online Exam Simulator"),
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
    post_total = 4

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/online-exam/manage-exams", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    # POST 1: Create Online Exam
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'title': 'Mid-Term CBT Evaluation Test',
            'kind': 'Exam',
            'school_class_id': 1,
            'section_id': 1,
            'subject_id': 1,
            'duration': 45,
            'total_marks': 50,
            'passing_percentage': 40,
            'instructions': 'Automated testing instructions.'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/online-exam/manage-exams", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/online-exam/manage-exams (Create Exam)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/online-exam/manage-exams")
    except Exception as e:
        print(f"[ERROR] POST /school/online-exam/manage-exams: {e}")

    # POST 2: Add Question to Bank
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'question_type': 'single_choice',
            'school_class_id': 1,
            'subject_id': 1,
            'difficulty': 'Medium',
            'question': 'What is the sum of angles in a planar triangle?',
            'marks': 2.0,
            'negative_marks': 0.5,
            'opt_a': '180 degrees',
            'opt_b': '360 degrees',
            'opt_c': '90 degrees',
            'opt_d': '270 degrees',
            'correct_answer': 'A',
            'solution_html': 'The interior angles of any planar Euclidean triangle always sum to 180 degrees.'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/online-exam/question-bank", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/online-exam/question-bank (Add Question)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/online-exam/question-bank")
    except Exception as e:
        print(f"[ERROR] POST /school/online-exam/question-bank: {e}")

    # POST 3: Add Chapter / Topic
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'subject_id': 1,
            'chapter': 'Chapter 5',
            'name': 'Triangles & Trigonometry',
            'description': 'Right-angled triangle properties and trigonometric ratios'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/online-exam/question-topics", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/online-exam/question-topics (Add Topic)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/online-exam/question-topics")
    except Exception as e:
        print(f"[ERROR] POST /school/online-exam/question-topics: {e}")

    # POST 4: Submit Student Exam Simulation
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'student_id': 1,
            'answers[1]': 'A',
            'answers[2]': 'A',
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/online-exam/take/1/submit", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/online-exam/take/1/submit (Submit Exam)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/online-exam/take/1/submit")
    except Exception as e:
        print(f"[ERROR] POST /school/online-exam/take/1/submit: {e}")

    print(f"\nPOST Actions: {post_passed}/{post_total} PASSED")

    if passed_count == total_count and post_passed == post_total:
        print("\nALL ONLINE EXAMINATIONS TESTS PASSED SUCCESSFULLY! (100% OK)")
        return 0
    else:
        print("\nSOME TESTS FAILED.")
        return 1

if __name__ == "__main__":
    sys.exit(main())
