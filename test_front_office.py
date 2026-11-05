import urllib.request
import urllib.parse
import http.cookiejar
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
opener.addheaders = [('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)')]

base = 'http://127.0.0.1:8000'

# Step 1: Login
print("1. Logging in as schooladmin...")
try:
    resp = opener.open(f'{base}/demo-login/schooladmin', timeout=10)
    print(f"Logged in: {resp.geturl()} (HTTP {resp.getcode()})")
except Exception as e:
    print(f"Failed to login: {e}")

# Step 2: Test all Front Office pages
test_urls = [
    ('/school/front-office/dashboard', 'Front Office Dashboard'),
    ('/school/front-office', 'Front Office Root Alias'),
    ('/school/front-office/admission-enquiries', 'Admission Enquiries List'),
    ('/school/front-office/admission-enquiries/create', 'Create Admission Enquiry Form'),
    ('/school/front-office/visitor-logs', 'Visitor Book List'),
    ('/school/front-office/visitor-logs/create', 'Create Visitor Entry Form'),
    ('/school/front-office/complaints', 'Complaints & Grievances List'),
    ('/school/front-office/postal-records', 'Postal Records List'),
    ('/school/front-office/gate-passes', 'Gate Passes List'),
    ('/school/front-office/gate-passes/gate', 'Gate Control Terminal'),
    ('/school/front-office/campus-workers', 'Campus Workers Registry'),
    ('/school/management/front-office', 'Legacy Front Office Alias'),
]

print("\n2. Testing GET requests on all Front Office pages:")
passed = 0
failed = 0

for path, label in test_urls:
    url = f"{base}{path}"
    try:
        resp = opener.open(url, timeout=10)
        code = resp.getcode()
        html = resp.read().decode('utf-8', errors='ignore')
        
        has_content = any(k in html for k in ['Front Office', 'Enquir', 'Visitor', 'Complaint', 'Gate', 'Postal', 'Worker'])
        if code == 200 and has_content:
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
    ('/school/front-office/admission-enquiries', {
        'student_name': 'Devansh Singhal',
        'parent_name': 'Alok Singhal',
        'phone': '9988776655',
        'enquiry_date': '2026-11-05',
        'admission_source': 'Walk In',
    }, 'Log Admission Enquiry'),
    ('/school/front-office/visitor-logs', {
        'visitor_name': 'Prof. H.S. Rawat',
        'phone': '9876541230',
        'purpose': 'Academic Audit',
        'person_to_meet': 'Principal',
    }, 'Check In Visitor'),
    ('/school/front-office/complaints', {
        'complainant_name': 'Sunita Rao (Parent)',
        'phone': '9871122334',
        'type': 'Transport',
        'complaint_date': '2026-11-05',
        'description': 'Bus 6 air conditioning fan needs check.',
    }, 'Log Complaint'),
    ('/school/front-office/postal-records', {
        'type': 'Dispatch',
        'date': '2026-11-05',
        'from_name': 'Exam Cell',
        'to_name': 'State Education Board',
        'reference_number': 'REF-DISP-991',
    }, 'Add Postal Record'),
    ('/school/front-office/campus-workers', {
        'name': 'Dharmendra Yadav',
        'category': 'Electrician',
        'valid_from': '2026-11-01',
        'valid_to': '2027-05-01',
        'police_verified': '1',
    }, 'Register Campus Worker'),
]

post_passed = 0
for path, data, label in post_tests:
    url = f"{base}{path}"
    try:
        # Get csrf token from a get page first
        get_resp = opener.open(f"{base}/school/front-office/dashboard", timeout=10)
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
    print("\nALL 100% FRONT OFFICE TESTS PASSED SUCCESSFULLY!")
else:
    print(f"\nSOME TESTS FAILED (GET failed: {failed}, POST failed: {len(post_tests) - post_passed})")

