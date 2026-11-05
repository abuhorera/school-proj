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

print("=== Starting Classrooms Module Automated Tests ===")
passed = 0
total = 0

# 1. Test GET Classroom List
total += 1
code, html = get("/school/classrooms/rooms")
if code == 200 and "Classroom List" in html:
    print("PASS: 1. GET /school/classrooms/rooms")
    passed += 1
else:
    print(f"FAIL: 1. GET /school/classrooms/rooms (HTTP {code})")

# 2. Test GET Buildings & Blocks
total += 1
code, html = get("/school/classrooms/buildings")
if code == 200 and "Buildings" in html:
    print("PASS: 2. GET /school/classrooms/buildings")
    passed += 1
else:
    print(f"FAIL: 2. GET /school/classrooms/buildings (HTTP {code})")

# 3. Test GET Room Label Matching
total += 1
code, html = get("/school/classrooms/labels")
if code == 200 and "Room Label" in html:
    print("PASS: 3. GET /school/classrooms/labels")
    passed += 1
else:
    print(f"FAIL: 3. GET /school/classrooms/labels (HTTP {code})")

# 4. Test GET Room Plan
total += 1
code, html = get("/school/classrooms/plan")
if code == 200 and "Room Plan" in html:
    print("PASS: 4. GET /school/classrooms/plan")
    passed += 1
else:
    print(f"FAIL: 4. GET /school/classrooms/plan (HTTP {code})")

# 5. Test GET Capacity Report
total += 1
code, html = get("/school/classrooms/plan/capacity")
if code == 200 and "Capacity" in html:
    print("PASS: 5. GET /school/classrooms/plan/capacity")
    passed += 1
else:
    print(f"FAIL: 5. GET /school/classrooms/plan/capacity (HTTP {code})")

# 6. Test GET Room Timetable
total += 1
code, html = get("/school/classrooms/timetable")
if code == 200 and "Room Timetable" in html:
    print("PASS: 6. GET /school/classrooms/timetable")
    passed += 1
else:
    print(f"FAIL: 6. GET /school/classrooms/timetable (HTTP {code})")

# 7. Test GET Free Rooms
total += 1
code, html = get("/school/classrooms/free-rooms")
if code == 200 and "Free Rooms" in html:
    print("PASS: 7. GET /school/classrooms/free-rooms")
    passed += 1
else:
    print(f"FAIL: 7. GET /school/classrooms/free-rooms (HTTP {code})")

# 8. Test GET Bookings
total += 1
code, html = get("/school/classrooms/bookings")
if code == 200 and "Bookings" in html:
    print("PASS: 8. GET /school/classrooms/bookings")
    passed += 1
else:
    print(f"FAIL: 8. GET /school/classrooms/bookings (HTTP {code})")

# 9. Test GET Maintenance
total += 1
code, html = get("/school/classrooms/maintenance")
if code == 200 and "Maintenance" in html:
    print("PASS: 9. GET /school/classrooms/maintenance")
    passed += 1
else:
    print(f"FAIL: 9. GET /school/classrooms/maintenance (HTTP {code})")

# 10. Test GET Usage Heatmap
total += 1
code, html = get("/school/classrooms/usage")
if code == 200 and "Usage" in html:
    print("PASS: 10. GET /school/classrooms/usage")
    passed += 1
else:
    print(f"FAIL: 10. GET /school/classrooms/usage (HTTP {code})")

# 11. Test GET Room Clashes
total += 1
code, html = get("/school/classrooms/clashes")
if code == 200 and "Clashes" in html:
    print("PASS: 11. GET /school/classrooms/clashes")
    passed += 1
else:
    print(f"FAIL: 11. GET /school/classrooms/clashes (HTTP {code})")

# 12. Test GET Classroom Guide
total += 1
code, html = get("/school/classrooms/guide")
if code == 200 and "Classroom Management Guide" in html:
    print("PASS: 12. GET /school/classrooms/guide")
    passed += 1
else:
    print(f"FAIL: 12. GET /school/classrooms/guide (HTTP {code})")

# 13. Test POST Create Building
total += 1
_, html = get("/school/classrooms/buildings")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/buildings", {
    '_token': csrf,
    'name': 'East Science Wing',
    'code': 'ESW',
    'floors': 3,
    'notes': 'Dedicated laboratories and research facilities',
    'is_active': '1'
})
if code == 200 or code == 302:
    print("PASS: 13. POST Create Building")
    passed += 1
else:
    print(f"FAIL: 13. POST Create Building (HTTP {code})")

# 14. Test POST Update Building
total += 1
_, html = get("/school/classrooms/buildings")
csrf = get_csrf(html)
soup = BeautifulSoup(html, 'html.parser')
first_building = soup.find('tr', {'data-id': True}) or soup.find('input', {'name': 'building_id'})
building_id = 1
code, resp = post(f"/school/classrooms/buildings/{building_id}", {
    '_token': csrf,
    'name': 'Main Academic Block (Renovated)',
    'code': 'MAB-1',
    'floors': 4,
    'is_active': '1'
})
if code == 200 or code == 302:
    print("PASS: 14. POST Update Building")
    passed += 1
else:
    print(f"FAIL: 14. POST Update Building (HTTP {code})")

# 15. Test POST Create Classroom Room
total += 1
_, html = get("/school/classrooms/rooms")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/rooms", [
    ('_token', csrf),
    ('room_number', 'ESW-101'),
    ('name', 'Robotics & AI Studio'),
    ('type', 'Lab'),
    ('capacity', '45'),
    ('exam_capacity', '30'),
    ('building_id', str(building_id)),
    ('floor', '1'),
    ('facilities[]', 'Projector'),
    ('facilities[]', 'Air Conditioning'),
    ('facilities[]', 'Audio System'),
    ('notes', 'State of the art lab setup')
])
if code == 200 or code == 302:
    print("PASS: 15. POST Create Room (ESW-101)")
    passed += 1
else:
    print(f"FAIL: 15. POST Create Room (HTTP {code})")

# 16. Test POST Quick Setup Bulk Generator
total += 1
_, html = get("/school/classrooms/rooms")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/quick-setup", {
    '_token': csrf,
    'building_id': str(building_id),
    'prefix': 'QS',
    'start_floor': 1,
    'end_floor': 2,
    'rooms_per_floor': 3,
    'room_type': 'Classroom',
    'capacity': 40
})
if code == 200 or code == 302:
    print("PASS: 16. POST Quick Setup Bulk Room Generation")
    passed += 1
else:
    print(f"FAIL: 16. POST Quick Setup Bulk Room Generation (HTTP {code})")

# 17. Test POST Update Classroom Room
total += 1
_, html = get("/school/classrooms/rooms")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/rooms/1", [
    ('_token', csrf),
    ('room_number', 'R-101-UPD'),
    ('name', 'Physics Lab A'),
    ('type', 'Lab'),
    ('capacity', '50'),
    ('exam_capacity', '35'),
    ('facilities[]', 'Projector')
])
if code == 200 or code == 302:
    print("PASS: 17. POST Update Classroom Room")
    passed += 1
else:
    print(f"FAIL: 17. POST Update Classroom Room (HTTP {code})")

# 18. Test POST Room Label Matching
total += 1
_, html = get("/school/classrooms/labels")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/labels/match", {
    '_token': csrf,
    'raw_label': 'Lab A (Physics)',
    'source': 'timetable',
    'room_id': '1'
})
if code == 200 or code == 302:
    print("PASS: 18. POST Room Label Matching")
    passed += 1
else:
    print(f"FAIL: 18. POST Room Label Matching (HTTP {code})")

# 19. Test POST Room Plan Save
total += 1
_, html = get("/school/classrooms/plan")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/plan/save", {
    '_token': csrf,
    'allocations[1]': '1'
})
if code == 200 or code == 302:
    print("PASS: 19. POST Save Master Room Plan")
    passed += 1
else:
    print(f"FAIL: 19. POST Save Master Room Plan (HTTP {code})")

# 20. Test POST Temporary Move Allocation
total += 1
_, html = get("/school/classrooms/plan")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/plan/temporary-move", {
    '_token': csrf,
    'section_id': '1',
    'source_room_id': '1',
    'target_room_id': '2',
    'start_date': '2026-11-10',
    'end_date': '2026-11-15',
    'reason': 'Annual whitewashing and electrical maintenance'
})
if code == 200 or code == 302:
    print("PASS: 20. POST Temporary Move Allocation")
    passed += 1
else:
    print(f"FAIL: 20. POST Temporary Move Allocation (HTTP {code})")

# 21. Test POST Create Room Booking
total += 1
_, html = get("/school/classrooms/bookings")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/bookings", {
    '_token': csrf,
    'room_id': '1',
    'booked_by': 'Prof. John Doe',
    'title': 'Science Olympiad Workshop',
    'booking_date': '2026-11-20',
    'start_time': '10:00',
    'end_time': '12:00',
    'reason': 'Special preparation for state olympiad'
})
if code == 200 or code == 302:
    print("PASS: 21. POST Create Room Booking")
    passed += 1
else:
    print(f"FAIL: 21. POST Create Room Booking (HTTP {code})")

# 22. Test POST Approve Room Booking
total += 1
_, html = get("/school/classrooms/bookings")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/bookings/1/approve", {
    '_token': csrf
})
if code == 200 or code == 302:
    print("PASS: 22. POST Approve Room Booking")
    passed += 1
else:
    print(f"FAIL: 22. POST Approve Room Booking (HTTP {code})")

# 23. Test POST Reject Room Booking
total += 1
_, html = get("/school/classrooms/bookings")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/bookings/1/reject", {
    '_token': csrf
})
if code == 200 or code == 302:
    print("PASS: 23. POST Reject Room Booking")
    passed += 1
else:
    print(f"FAIL: 23. POST Reject Room Booking (HTTP {code})")

# 24. Test POST Save Booking Settings
total += 1
_, html = get("/school/classrooms/bookings")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/bookings/settings", {
    '_token': csrf,
    'require_approval': '1',
    'allow_teacher_override': '0',
    'advance_days_max': '45',
    'auto_cancel_unapproved_hours': '24'
})
if code == 200 or code == 302:
    print("PASS: 24. POST Save Booking Settings")
    passed += 1
else:
    print(f"FAIL: 24. POST Save Booking Settings (HTTP {code})")

# 25. Test POST Log Room Maintenance Job
total += 1
_, html = get("/school/classrooms/maintenance")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/maintenance", {
    '_token': csrf,
    'room_id': '1',
    'problem': 'Ceiling fan making loud noise and vibrating',
    'priority': 'Urgent',
    'given_to': 'Electrician Mike',
    'is_unusable': '1',
    'notes': 'Switched off at circuit board for safety'
})
if code == 200 or code == 302:
    print("PASS: 25. POST Log Maintenance Ticket")
    passed += 1
else:
    print(f"FAIL: 25. POST Log Maintenance Ticket (HTTP {code})")

# 26. Test POST Update Maintenance Job Status
total += 1
_, html = get("/school/classrooms/maintenance")
csrf = get_csrf(html)
code, resp = post("/school/classrooms/maintenance/1", {
    '_token': csrf,
    'status': 'Resolved',
    'given_to': 'Electrician Mike',
    'notes': 'Fan bearings replaced and tested successfully'
})
if code == 200 or code == 302:
    print("PASS: 26. POST Update Maintenance Ticket")
    passed += 1
else:
    print(f"FAIL: 26. POST Update Maintenance Ticket (HTTP {code})")

# 27. Test GET Free Rooms Query Parameters
total += 1
code, html = get("/school/classrooms/free-rooms?day=Monday&period=1&building_id=1")
if code == 200 and "Free Rooms" in html:
    print("PASS: 27. GET /school/classrooms/free-rooms with day and period filters")
    passed += 1
else:
    print(f"FAIL: 27. GET /school/classrooms/free-rooms with filters (HTTP {code})")

# 28. Test GET Maintenance tab filter
total += 1
code, html = get("/school/classrooms/maintenance?tab=done")
if code == 200 and "Maintenance" in html:
    print("PASS: 28. GET /school/classrooms/maintenance with ?tab=done")
    passed += 1
else:
    print(f"FAIL: 28. GET /school/classrooms/maintenance with ?tab=done (HTTP {code})")

print(f"\nClassrooms Module Test Result: {passed}/{total} Passed ({passed/total*100:.1f}%)")
if passed == total:
    print("ALL CLASSROOMS TESTS PASSED SUCCESSFULLY!")
    sys.exit(0)
else:
    print("SOME TESTS FAILED!")
    sys.exit(1)

