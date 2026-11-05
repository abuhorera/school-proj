import urllib.request
import urllib.parse
import http.cookiejar
import re
import sys

BASE_URL = "http://127.0.0.1:8000"

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

def get(path):
    url = f"{BASE_URL}{path}"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        resp = opener.open(req)
        body = resp.read().decode('utf-8')
        return resp.getcode(), body
    except urllib.error.HTTPError as e:
        body = e.read().decode('utf-8') if e.fp else ""
        return e.code, body
    except Exception as e:
        return 0, str(e)

def post(path, data):
    url = f"{BASE_URL}{path}"
    encoded = urllib.parse.urlencode(data).encode('utf-8')
    req = urllib.request.Request(url, data=encoded, headers={
        'User-Agent': 'Mozilla/5.0',
        'Content-Type': 'application/x-www-form-urlencoded'
    })
    try:
        resp = opener.open(req)
        body = resp.read().decode('utf-8')
        return resp.getcode(), body
    except urllib.error.HTTPError as e:
        body = e.read().decode('utf-8') if e.fp else ""
        return e.code, body
    except Exception as e:
        return 0, str(e)

def extract_token(html):
    m = re.search(r'name="_token"\s+value="([^"]+)"', html)
    if not m:
        m = re.search(r'value="([^"]+)"\s+name="_token"', html)
    return m.group(1) if m else None

print("=== STARTING OFFLINE EXAMINATIONS END-TO-END VERIFICATION ===")

# 1. Login
code, html = get("/demo-login/admin")
print(f"[AUTH] Demo login status: {code}")
token = extract_token(html)
print(f"[AUTH] CSRF token: {token[:10] if token else 'None'}...")

# 2. Test GET routes across all 15 examination views
routes = [
    ("/school/exams-dashboard", "Exam Dashboard"),
    ("/school/exams", "Manage Offline Exams Directory"),
    ("/school/exams/create", "Add New Exam"),
    ("/school/exam-types", "Manage Exam Types"),
    ("/school/exam-schedule", "Exam Schedule & Marks Setup"),
    ("/school/exam-marks/entry", "Enter Exam Marks"),
    ("/school/enter-marks", "Enter Marks (Alias)"),
    ("/school/cocurricular-grades", "Cocurricular Evaluation"),
    ("/school/grades", "Manage Grading Scales (CBSE 9-Point)"),
    ("/school/marksheet", "Generate Marksheets Directory"),
    ("/school/marksheet/1/1", "Printable CBSE Report Card"),
    ("/school/marksheet/upload", "Upload External Marksheet"),
    ("/school/marksheet/upload-list", "Manage Uploaded Marksheets"),
    ("/school/marksheet-remarks", "Teacher Report Card Remarks"),
    ("/school/admit-cards", "Admit Cards (Hall Tickets)"),
    ("/school/admit-cards/print/1/1", "Printable Examination Hall Ticket"),
]

passed_gets = 0
for r, label in routes:
    c, body = get(r)
    status = "PASS" if c == 200 else "FAIL"
    if c == 200:
        passed_gets += 1
    print(f"[{status}] GET {r} - {label} (HTTP {c})")

print(f"\nGET Routes Result: {passed_gets}/{len(routes)} PASSED")

# 3. Test POST - Create Exam
print("\n[TEST] Creating New Exam...")
code, page_html = get("/school/exams/create")
csrf_token = extract_token(page_html)

exam_data = {
    '_token': csrf_token,
    'name': 'Half-Yearly Evaluation Test 2026',
    'academic_session_id': '1',
    'type': 'Term Exam',
    'start_date': '2026-10-15',
    'end_date': '2026-10-28',
    'description': 'Mid-session comprehensive evaluation',
    'status': 'Active'
}
c, _ = post("/school/exams", exam_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/exams -> HTTP {c}")

# Verify Exam exists in list
c, list_html = get("/school/exams")
exam_found = "Half-Yearly Evaluation Test" in list_html
print(f"[{'PASS' if exam_found else 'FAIL'}] Verified Exam in Directory: {exam_found}")

# 4. Test POST - Store Exam Type
type_data = {
    '_token': csrf_token,
    'name': 'Listening & Speaking Skills',
    'abbreviation': 'ASL',
    'weightage': '10%',
    'sort_order': '8',
    'description': 'Assessment of Speaking and Listening in English'
}
c, _ = post("/school/exam-types", type_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/exam-types -> HTTP {c}")

# 5. Test POST - Store Schedule Slot
schedule_data = {
    '_token': csrf_token,
    'exam_id': '1',
    'class_id': '1',
    'subject_id': '1',
    'exam_date': '2026-11-20',
    'start_time': '09:00',
    'end_time': '12:00',
    'room_no': 'Exam Hall 101',
    'max_marks': '100',
    'pass_marks': '33'
}
c, _ = post("/school/exam-schedule", schedule_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/exam-schedule -> HTTP {c}")

# 6. Test POST - Save Student Marks
marks_data = {
    '_token': csrf_token,
    'exam_schedule_id': '1',
    'marks[1]': '88',
    'remarks[1]': 'Very good performance'
}
c, _ = post("/school/exam-marks/save", marks_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/exam-marks/save -> HTTP {c}")

# 7. Test POST - Save Cocurricular Grades
cocurr_data = {
    '_token': csrf_token,
    'exam_id': '1',
    'grades[1][Work Education (Cookery, Gardening)]': 'A+',
    'grades[1][Art Education (Drawing & Sketching)]': 'A',
    'grades[1][Health & Physical Education (Sports)]': 'A+',
    'grades[1][Discipline & Moral Ethics]': 'A'
}
c, _ = post("/school/cocurricular-grades/save", cocurr_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/cocurricular-grades/save -> HTTP {c}")

# 8. Test POST - Store Grade Tier
grade_data = {
    '_token': csrf_token,
    'name': 'O',
    'min_percentage': '98',
    'max_percentage': '100',
    'grade_point': '10.0',
    'description': 'Exceptional Distinction'
}
c, _ = post("/school/grades", grade_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/grades -> HTTP {c}")

# 9. Test POST - Save Teacher Remarks
remarks_data = {
    '_token': csrf_token,
    'exam_id': '1',
    'remarks[1]': 'Diligent and enthusiastic learner with strong analytical skills.',
    'conduct[1]': 'Exemplary',
    'attendance[1]': '96.5'
}
c, _ = post("/school/marksheet-remarks/save", remarks_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/marksheet-remarks/save -> HTTP {c}")

# 10. Test POST - Upload Marksheet
upload_data = {
    '_token': csrf_token,
    'student_id': '1',
    'exam_id': '1',
    'class_id': '1',
    'section_id': '1',
    'title': 'State Science Fair Gold Merit Certificate'
}
c, _ = post("/school/marksheet/upload", upload_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/marksheet/upload -> HTTP {c}")

# Final verify in upload-list
c, up_html = get("/school/marksheet/upload-list")
upload_verified = "State Science Fair Gold Merit Certificate" in up_html
print(f"[{'PASS' if upload_verified else 'FAIL'}] Verified Marksheet in Upload Directory: {upload_verified}")

all_success = passed_gets == len(routes) and exam_found and upload_verified
print(f"\n=== OVERALL OFFLINE EXAMINATIONS TEST RESULT: {'100% PASSED' if all_success else 'FAILED'} ===")
sys.exit(0 if all_success else 1)
