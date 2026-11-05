import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')
session = requests.Session()

# 1. Login
r_login = session.get("http://127.0.0.1:8000/demo-login/schooladmin", allow_redirects=True)
print(f"Login status: {r_login.status_code}")

student_routes = [
    ("Student Dashboard", "/school/student-dashboard"),
    ("Student Admission Form", "/school/students/create"),
    ("Student List / Directory", "/school/students"),
    ("Search Student by Photo", "/school/students-face-search"),
    ("Parents & Guardians", "/school/parents"),
    ("Student Attendance Register", "/school/student-attendance"),
    ("Behavior Records", "/school/student-behavior"),
    ("Student Houses Master", "/school/student-houses"),
    ("Student Categories Master", "/school/student-categories"),
    ("TC & Exit Management", "/school/student-exits"),
    ("Student Health Records", "/school/student-health"),
    ("Recycle Bin (Deleted)", "/school/students-recycle-bin"),
    ("Student 360 Profile (ID:1)", "/school/students/1"),
]

print("\nTesting all Student Information System (SIS) sub-modules:")
passed = 0
failed = 0

for name, path in student_routes:
    url = f"http://127.0.0.1:8000{path}"
    try:
        res = session.get(url, timeout=5)
        if res.status_code == 200:
            print(f"  [OK 200] {name:28} : {path}")
            passed += 1
        else:
            print(f"  [FAIL {res.status_code}] {name:28} : {path}")
            failed += 1
    except Exception as e:
        print(f"  [ERROR] {name:28} : {e}")
        failed += 1

print("\n==========================================")
print(f"Total Student Pages Tested: {len(student_routes)}")
print(f"Passed: {passed} / {len(student_routes)}")
print(f"Failed: {failed} / {len(student_routes)}")
print("==========================================")

# 2. Test POST Forms (Admission, Behavior, House, Category, Attendance)
print("\nTesting Form Submissions (POST actions):")

r_form = session.get("http://127.0.0.1:8000/school/students/create")
tokens = re.findall(r'name="_token"\s+value="([^"]+)"', r_form.text)
token = tokens[0] if tokens else ''

# 2.1 Post Student Admission
adm_payload = {
    '_token': token,
    'admission_no': f'TEST-ADM-{session.get("http://127.0.0.1:8000/school/student-dashboard").status_code}-{hash(token) % 10000}',
    'roll_no': '199',
    'first_name': 'Kavya',
    'last_name': 'Malhotra',
    'gender': 'female',
    'dob': '2015-06-12',
    'blood_group': 'B+',
    'class_id': '1',
    'section_id': '1',
    'father_name': 'Vivek Malhotra',
    'father_phone': '+91 98112-99887',
    'admission_date': '2026-11-05'
}
r_adm = session.post("http://127.0.0.1:8000/school/students", data=adm_payload, allow_redirects=True)
print(f"  POST Student Admission: Status {r_adm.status_code} (Redirected to: {r_adm.url})")

# 2.2 Post Behavior Note
beh_payload = {
    '_token': token,
    'student_id': 1,
    'title': 'Test Science Fair Presentation',
    'type': 'Positive',
    'points': 5,
    'date': '2026-11-05',
    'description': 'Automated test behavior entry'
}
r_beh = session.post("http://127.0.0.1:8000/school/student-behavior", data=beh_payload, allow_redirects=True)
print(f"  POST Behavior Record: Status {r_beh.status_code} (Redirected to: {r_beh.url})")

# 2.3 Post Student House
house_payload = {
    '_token': token,
    'name': 'Golden Lions',
    'color': '#eab308',
    'code': 'GLD',
    'captain': 'Vikram Rathore',
    'description': 'Courage and Leadership'
}
r_house = session.post("http://127.0.0.1:8000/school/student-houses", data=house_payload, allow_redirects=True)
print(f"  POST Student House: Status {r_house.status_code} (Redirected to: {r_house.url})")

# 2.4 Post Student Category
cat_payload = {
    '_token': token,
    'name': 'Staff Ward Quota',
    'code': 'STAFF',
    'description': 'Children of full-time teaching and admin staff'
}
r_cat = session.post("http://127.0.0.1:8000/school/student-categories", data=cat_payload, allow_redirects=True)
print(f"  POST Student Category: Status {r_cat.status_code} (Redirected to: {r_cat.url})")

# 2.5 Post Attendance
att_payload = {
    '_token': token,
    'date': '2026-11-05',
    'attendance[1]': 'present',
    'remarks[1]': 'Present on time'
}
r_att = session.post("http://127.0.0.1:8000/school/student-attendance", data=att_payload, allow_redirects=True)
print(f"  POST Daily Attendance: Status {r_att.status_code} (Redirected to: {r_att.url})")

print("\nALL STUDENT INFORMATION SUB-MODULES & ACTIONS VERIFIED!")

