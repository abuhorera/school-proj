import urllib.request
import urllib.parse
import http.cookiejar
from bs4 import BeautifulSoup
import sys
import re

sys.stdout.reconfigure(encoding='utf-8')

BASE_URL = "http://127.0.0.1:8000"
cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

def get(path):
    url = f"{BASE_URL}{path}"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        resp = opener.open(req)
        return resp.getcode(), resp.read().decode('utf-8', errors='ignore')
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf-8', errors='ignore')

def post(path, data=None):
    url = f"{BASE_URL}{path}"
    encoded = urllib.parse.urlencode(data).encode('utf-8') if data else b""
    req = urllib.request.Request(url, data=encoded, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        resp = opener.open(req)
        return resp.getcode(), resp.read().decode('utf-8', errors='ignore')
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf-8', errors='ignore')

def get_csrf(html):
    soup = BeautifulSoup(html, 'html.parser')
    token_tag = soup.find('input', {'name': '_token'})
    if token_tag:
        return token_tag.get('value')
    meta_tag = soup.find('meta', {'name': 'csrf-token'})
    if meta_tag:
        return meta_tag.get('content')
    m = re.search(r'name="_token"\s+value="([^"]+)"', html)
    return m.group(1) if m else ''

print("=== Starting Holistic Progress Card (HPC) Test Suite ===")

# 0. Log in
code, _ = get("/demo-login/schooladmin")
print(f"Login response status: {code}")
assert code in (200, 302), f"Login failed with code {code}"

tests_passed = 0
tests_total = 0

def test(name, condition, extra=""):
    global tests_passed, tests_total
    tests_total += 1
    if condition:
        tests_passed += 1
        print(f"  [PASS] {name} {extra}")
    else:
        print(f"  [FAIL] {name} {extra}")

# 1. Dashboard
code, html = get("/school/hpc/dashboard")
test("HPC Dashboard Loads", code == 200 and "Holistic Progress Card" in html and "Editions Adopted" in html)

# 2. Activities List
code, html = get("/school/hpc/activities")
test("HPC Activities List Loads", code == 200 and "HPC Activities" in html and "Aesthetic and Cultural Development" in html)

# 3. Create Activity Page
code, html = get("/school/hpc/activities/create")
csrf = get_csrf(html)
test("Create Activity Page Loads", code == 200 and "New Classroom Activity" in html and csrf != "")

# 4. Store Activity
code, resp = post("/school/hpc/activities", {
    '_token': csrf,
    'title': 'Automated Test Rhythm Quest',
    'school_class_id': 1,
    'domain': 'Aesthetic and Cultural Development',
    'activity_type': 'Learning Activity',
    'interaction_type': 'Small Group',
    'duration_minutes': 40,
    'prompt': 'Test activity prompt for learners',
})
test("Store Activity POST", code == 200 and "created successfully" in resp.lower())

# Extract activity ID for marking
m_act = re.search(r'/school/hpc/activities/(\d+)/mark', resp)
act_id = m_act.group(1) if m_act else '1'

# 5. Mark Activity Page
code, html = get(f"/school/hpc/activities/{act_id}/mark")
csrf = get_csrf(html)
test(f"Mark Activity Page Loads (ID: {act_id})", code == 200 and "Mark —" in html and "Attainment Rubric" in html)

# 6. Save Activity Marks
code, resp = post(f"/school/hpc/activities/{act_id}/mark", {
    '_token': csrf,
    'levels[1][Awareness]': 'A',
    'levels[1][Sensitivity]': 'P',
    'levels[1][Creativity]': 'A',
    'observations[1]': 'Demonstrated high enthusiasm and creative rhythm coordination.',
    'challenges[1]': 'None observed.',
})
test("Save Activity Marks POST", code == 200 and "marks and rubrics saved" in resp.lower())

# 7. Progress Cards List
code, html = get("/school/hpc/cards")
test("Progress Cards List Loads", code == 200 and "Progress Cards" in html and "Filled in" in html)

# 8. Create Cards Step 1
code, html = get("/school/hpc/cards/create")
test("Create Cards Step 1 Loads", code == 200 and "Create Progress Cards" in html and "Step 1 — Which class?" in html)

# 9. Create Cards Step 2 (with class selected)
code, html = get("/school/hpc/cards/create?school_class_id=1&term=Full+year")
csrf = get_csrf(html)
test("Create Cards Step 2 Loads", code == 200 and "Step 2 — Select Learners" in html and csrf != "")

# 10. Store Bulk Cards
code, resp = post("/school/hpc/cards", {
    '_token': csrf,
    'school_class_id': 1,
    'term': 'Full year',
    'student_ids[]': [1, 2],
})
test("Bulk Store Cards POST", code == 200 and "card(s) generated successfully" in resp.lower())

# 11. Show Progress Card Detail
code, html = get("/school/hpc/cards/1")
csrf = get_csrf(html)
test("Card Detail Page Loads", code == 200 and "Part A(1)" in html and "Health Profile" in html and csrf != "")

# 12. Update Section A1 (General Info)
code, resp = post("/school/hpc/cards/1/sections/A1", {
    '_token': csrf,
    'fields[school_name]': 'YUG INTERNATIONAL SCHOOL',
    'fields[height_cm]': 118,
    'fields[weight_kg]': 20.2,
})
test("Update Section A1 POST", code == 200 and "section a1 saved successfully" in resp.lower())

# 13. Update Section A2 (Self Assessment)
code, resp = post("/school/hpc/cards/1/sections/A2", {
    '_token': csrf,
    'self_assessment[likes]': 'I enjoy watercolor painting and listening to folklore stories.',
    'self_assessment[strengths]': 'I help my friends clean up the toy corners.',
    'self_assessment[goals]': 'I want to read long storybooks independently.',
    'self_assessment[favorite_corner]': 'Discovery Corner',
    'self_assessment[self_emoji]': 'Curious & Creative',
})
test("Update Section A2 (Self Assessment) POST", code == 200 and "section a2 saved successfully" in resp.lower())

# 14. Update Section A3 (Peer Assessment)
code, resp = post("/school/hpc/cards/1/sections/A3", {
    '_token': csrf,
    'peer_assessment[peer_name]': 'Aarav Sharma (Roll 1)',
    'peer_assessment[peer_remarks]': 'Very kind and shares toys nicely during outdoor playtime.',
    'peer_assessment[cooperation_rating]': 'Always Helpful & Cooperative',
})
test("Update Section A3 (Peer Assessment) POST", code == 200 and "section a3 saved successfully" in resp.lower())

# 15. Update Section A4 (Parent Feedback)
code, resp = post("/school/hpc/cards/1/sections/A4", {
    '_token': csrf,
    'parent_feedback[parent_name]': 'Mrs. & Mr. Sharma',
    'parent_feedback[habits_at_home]': 'Enjoys bedtime books and shares daily school events cheerfully.',
    'parent_feedback[parent_observation]': 'Shows high curiosity for animals and colors.',
    'parent_feedback[suggestions]': 'Very pleased with progress.',
})
test("Update Section A4 (Parent Feedback) POST", code == 200 and "section a4 saved successfully" in resp.lower())

# 16. Update Section B (Domain Rubrics)
code, resp = post("/school/hpc/cards/1/sections/B", {
    '_token': csrf,
    'domain_scores[Physical Development][Gross motor balance]': 'A',
    'domain_scores[Physical Development][Fine motor coordination]': 'P',
    'domain_scores[Cognitive Development][Pattern recognition]': 'A',
})
test("Update Section B (Domain Rubrics) POST", code == 200 and "section b saved successfully" in resp.lower())

# 17. Update Section C (Annual Summary)
code, resp = post("/school/hpc/cards/1/sections/C", {
    '_token': csrf,
    'teacher_summary[teacher_name]': 'Ms. Ananya Sen',
    'teacher_summary[holistic_remarks]': 'Outstanding holistic development across domains.',
    'teacher_summary[principal_remarks]': 'Promoted with distinction to next stage.',
})
test("Update Section C (Annual Summary) POST", code == 200 and "section c saved successfully" in resp.lower())

# 18. Card Review Transition
code, resp = post("/school/hpc/cards/1/review", {'_token': csrf})
test("Card Review Transition POST", code == 200 and "reviewed" in resp.lower())

# 19. Card Return with Reason
code, resp = post("/school/hpc/cards/1/return", {
    '_token': csrf,
    'reason': 'Please double check Part A(3) peer observation before final sign-off.',
})
test("Card Return with Reason POST", code == 200 and "returned to teacher" in resp.lower())

# 20. Card Publish Transition
code, resp = post("/school/hpc/cards/1/publish", {'_token': csrf})
test("Card Publish Transition POST", code == 200 and "published" in resp.lower())

# 21. Official Printable Card Preview
code, html = get("/school/hpc/cards/1/preview")
test("Official Printable Card Preview", code == 200 and "HOLISTIC PROGRESS CARD" in html and "Learner Profile & Health Metrics" in html)

# 22. Colour Illustrated Keepsake Card (Family version)
code, html = get("/school/hpc/cards/1/colour?theme=playful")
test("Colour Keepsake Card Loads", code == 200 and "A Little About Me" in html and "Developmental Abilities & Competency Medals" in html)

# 23. Frameworks List
code, html = get("/school/hpc/frameworks")
test("Card Editions List Loads", code == 200 and "Card Editions" in html and "Foundational Stage" in html)

# 24. Framework Detail Page
code, html = get("/school/hpc/frameworks/1")
csrf = get_csrf(html)
test("Framework Detail Page Loads", code == 200 and "Classes using this edition" in html and csrf != "")

# 25. Assign Classes to Framework
code, resp = post("/school/hpc/frameworks/1/classes", [
    ('_token', csrf),
    ('school_class_ids[]', '1'),
    ('school_class_ids[]', '2'),
    ('school_class_ids[]', '3'),
])
test("Assign Classes to Framework POST", code == 200 and "assigned to this card edition successfully" in resp.lower())

# 26. Card Appearance Page
code, html = get("/school/hpc/card-appearance")
csrf = get_csrf(html)
test("Card Appearance Page Loads", code == 200 and "Card Appearance" in html and "Edition Label Stamp" in html and csrf != "")

# 27. Update Edition Label Stamp
code, resp = post("/school/hpc/card-appearance/edition-label", {
    '_token': csrf,
    'edition_label': 'PARAKH NEP-2020 Validated',
})
test("Update Edition Label Stamp POST", code == 200 and "configuration saved successfully" in resp.lower())

# 28. Update Card Appearance Settings
code, resp = post("/school/hpc/card-appearance", {
    '_token': csrf,
    'theme': 'playful',
    'show_monthly_attendance': '1',
    'show_health_metrics': '1',
    'show_radar_chart': '1',
})
test("Update Appearance Preferences POST", code == 200 and "configuration saved successfully" in resp.lower())

print(f"\nTotal Tests: {tests_total} | Passed: {tests_passed} | Failed: {tests_total - tests_passed}")
if tests_passed == tests_total:
    print("SUCCESS: 100% of HPC tests passed!")
    sys.exit(0)
else:
    print("FAILURE: Some tests failed.")
    sys.exit(1)
