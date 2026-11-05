import urllib.request
import urllib.parse
import http.cookiejar
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
opener.addheaders = [('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)')]

base = 'http://127.0.0.1:8000'

# Step 1: Login via demo login
print("1. Logging in as schooladmin...")
try:
    resp = opener.open(f'{base}/demo-login/schooladmin', timeout=10)
    print(f"Logged in: {resp.geturl()} (HTTP {resp.getcode()})")
except Exception as e:
    print(f"Failed to login: {e}")

# Step 2: Test all 11 Academics pages + aliases
test_urls = [
    ('/school/academics/dashboard', 'Academic Dashboard'),
    ('/school/academics', 'Academic Dashboard Alias'),
    ('/school/academic-sessions', 'Academic Sessions'),
    ('/school/classes', 'Classes Master'),
    ('/school/sections', 'Sections Directory'),
    ('/school/subjects', 'Subjects Catalog'),
    ('/school/assign-subjects', 'Assign Subjects to Classes'),
    ('/school/assign-electives', 'Assign Elective Subjects'),
    ('/school/assign-class-teacher', 'Assign Subject Teacher'),
    ('/school/time-slots', 'Manage Periods & Time Slots'),
    ('/school/period-sets', 'Period Sets Alias'),
    ('/school/timetable', 'Class Timetable Matrix'),
    ('/school/promote-students', 'Promote Students Wizard'),
    ('/school/academics/promote', 'Promote Alias'),
]

print("\n2. Testing GET requests on all Academics pages:")
passed = 0
failed = 0

for path, label in test_urls:
    url = f"{base}{path}"
    try:
        resp = opener.open(url, timeout=10)
        code = resp.getcode()
        html = resp.read().decode('utf-8', errors='ignore')
        
        # Check title or key content
        has_academic = 'Academic' in html or 'Class' in html or 'Period' in html or 'Session' in html
        if code == 200 and has_academic:
            print(f"  [PASS] {code} OK - {label} ({path})")
            passed += 1
        else:
            print(f"  [FAIL] {code} - {label} ({path}) - Key text missing")
            failed += 1
    except Exception as e:
        print(f"  [FAIL] ERROR - {label} ({path}): {e}")
        failed += 1

print(f"\nGET Results: {passed}/{len(test_urls)} passed ({failed} failed)")

# Step 3: Test POST requests
print("\n3. Testing POST actions:")
post_tests = [
    ('/school/academic-sessions', {
        'name': '2028-2029 (28-29)',
        'start_date': '2028-04-01',
        'end_date': '2029-03-31',
    }, 'Create Academic Session'),
    ('/school/classes', {
        'name': 'Class 12 Commerce',
        'numeric_level': '12',
    }, 'Create Class'),
    ('/school/sections', {
        'class_id': '1',
        'name': 'C',
        'capacity': '35',
    }, 'Create Section'),
    ('/school/subjects', {
        'name': 'Robotics & AI',
        'code': 'ROB-101',
        'type': 'Both',
        'group': 'Vocational & Arts',
        'is_elective': '1',
    }, 'Create Subject'),
    ('/school/time-slots', {
        'name': 'Zero Period',
        'start_time': '07:30',
        'end_time': '08:00',
        'sort_order': '0',
    }, 'Create Time Slot'),
]

post_passed = 0
for path, data, label in post_tests:
    url = f"{base}{path}"
    try:
        # Get csrf token from a get page first
        get_resp = opener.open(f"{base}/school/academic-sessions", timeout=10)
        html = get_resp.read().decode('utf-8', errors='ignore')
        token_match = re.search(r'name=\"_token\"\s+value=\"([^\"]+)\"', html)
        token = token_match.group(1) if token_match else ''
        
        data['_token'] = token
        encoded_data = urllib.parse.urlencode(data).encode('utf-8')
        req = urllib.request.Request(url, data=encoded_data, method='POST')
        resp = opener.open(req, timeout=10)
        print(f"  [PASS] {resp.getcode()} - {label} ({path})")
        post_passed += 1
    except Exception as e:
        print(f"  [FAIL] {label} ({path}): {e}")

print(f"\nPOST Results: {post_passed}/{len(post_tests)} passed")

if failed == 0 and post_passed == len(post_tests):
    print("\nALL 100% ACADEMICS TESTS PASSED SUCCESSFULLY!")
else:
    print(f"\nSOME TESTS FAILED (GET failed: {failed}, POST failed: {len(post_tests) - post_passed})")

