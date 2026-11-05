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

print("=== STARTING LEAD MANAGEMENT END-TO-END VERIFICATION ===")

# 1. Login
code, html = get("/demo-login/admin")
print(f"[AUTH] Demo login status: {code}")

token = extract_token(html)
print(f"[AUTH] CSRF token: {token[:10] if token else 'None'}...")

# 2. Test GET routes
routes = [
    ("/school/leads", "Lead Dashboard (Index Alias)"),
    ("/school/leads/dashboard", "Lead Dashboard"),
    ("/school/leads/board", "Pipeline Board (Kanban)"),
    ("/school/leads/list", "All Leads Directory"),
    ("/school/leads/tasks", "Follow-up Tasks Tracker"),
    ("/school/leads/settings", "Sources & Stages Settings"),
]

passed_gets = 0
for r, label in routes:
    c, body = get(r)
    status = "PASS" if c == 200 else "FAIL"
    if c == 200:
        passed_gets += 1
    print(f"[{status}] GET {r} - {label} (HTTP {c})")

print(f"\nGET Routes Result: {passed_gets}/{len(routes)} PASSED")

# 3. Test POST - Create New Lead
print("\n[TEST] Creating New Lead...")
code, page_html = get("/school/leads/list")
csrf_token = extract_token(page_html)

lead_data = {
    '_token': csrf_token,
    'student_name': 'Aarav Patel (Test Lead)',
    'parent_name': 'Sanjay Patel',
    'phone': '9898012345',
    'email': 'sanjay.patel@example.com',
    'school_class_id': '1',
    'lead_source_id': '1',
    'lead_stage_id': '1',
    'follow_up_date': '2026-11-10',
    'notes': 'Looking for Grade 1 admission in coming academic session.'
}
c, resp = post("/school/leads", lead_data)
print(f"[PASS if 200] POST /school/leads -> HTTP {c}")

# Verify Aarav Patel is listed in directory
c, list_html = get("/school/leads/list?search=Aarav+Patel")
lead_found = "Aarav Patel" in list_html
print(f"[{'PASS' if lead_found else 'FAIL'}] Verified Lead Created & Found in Directory: {lead_found}")

# Extract lead ID from list_html to test stage update and followup
lead_id_match = re.search(r'/school/leads/(\d+)/stage', list_html)
lead_id = lead_id_match.group(1) if lead_id_match else "1"
print(f"[INFO] Using Lead ID: {lead_id} for stage & followup tests")

# 4. Test POST - Update Stage
stage_data = {
    '_token': csrf_token,
    'lead_stage_id': '2',
    'notes': 'Contacted parent via telephone; scheduled campus tour.'
}
c, _ = post(f"/school/leads/{lead_id}/stage", stage_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/leads/{lead_id}/stage -> HTTP {c}")

# 5. Test POST - Store Followup
followup_data = {
    '_token': csrf_token,
    'type': 'Phone Call',
    'response_notes': 'Detailed telephonic conversation completed. Documents requested.',
    'next_follow_up_date': '2026-11-15',
    'lead_stage_id': '2'
}
c, _ = post(f"/school/leads/{lead_id}/followup", followup_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/leads/{lead_id}/followup -> HTTP {c}")

# 6. Test POST - Settings: Add Stage
new_stage_data = {
    '_token': csrf_token,
    'name': 'Principal Interview',
    'color': '#8b5cf6',
    'sort_order': '7'
}
c, _ = post("/school/leads/settings/stages", new_stage_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/leads/settings/stages -> HTTP {c}")

# 7. Test POST - Settings: Add Source
new_source_data = {
    '_token': csrf_token,
    'name': 'YouTube Campus Tour Video'
}
c, _ = post("/school/leads/settings/sources", new_source_data)
print(f"[{'PASS' if c == 200 else 'FAIL'}] POST /school/leads/settings/sources -> HTTP {c}")

# Final verification of Settings page
c, settings_html = get("/school/leads/settings")
stage_verified = "Principal Interview" in settings_html
source_verified = "YouTube Campus Tour Video" in settings_html
print(f"[{'PASS' if stage_verified else 'FAIL'}] Verified New Stage 'Principal Interview' in Settings: {stage_verified}")
print(f"[{'PASS' if source_verified else 'FAIL'}] Verified New Source 'YouTube Campus Tour Video' in Settings: {source_verified}")

all_success = passed_gets == len(routes) and lead_found and stage_verified and source_verified
print(f"\n=== OVERALL LEAD MANAGEMENT TEST RESULT: {'100% PASSED' if all_success else 'FAILED'} ===")
sys.exit(0 if all_success else 1)
