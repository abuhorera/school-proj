import urllib.request
import urllib.parse
import http.cookiejar
from bs4 import BeautifulSoup
import sys

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

print("=== Starting 19 Modules & Facilities Automated Tests ===")
passed = 0
total = 0

routes = [
    ("/school/modules/live-classes", "Live Classes", "1. Live Classes"),
    ("/school/modules/apps", "Apps Center", "2. Apps Center"),
    ("/school/modules/study-center", "Study Center", "3. Study Center"),
    ("/school/modules/certificates", "Certificates", "4. Certificates"),
    ("/school/modules/id-cards", "ID Cards", "5. ID Cards"),
    ("/school/modules/communicate", "Notice Board", "6. Communicate"),
    ("/school/modules/library", "Library", "7. Library Management"),
    ("/school/modules/inventory", "Inventory", "8. Inventory & Stock"),
    ("/school/modules/transport", "Transport", "9. Transport Management"),
    ("/school/modules/hostel", "Hostel", "10. Hostel Management"),
    ("/school/modules/help-center", "Help Center", "11. Help Center & KB"),
    ("/school/modules/assets", "Asset Management", "12. Asset Management"),
    ("/school/modules/engagement", "Engagement", "13. Engagement & Creatives"),
    ("/school/modules/cctv", "CCTV", "14. CCTV Live Wall"),
    ("/school/modules/alumni", "Alumni", "15. Alumni Management"),
    ("/school/modules/labs", "Lab Management", "16. Lab Management"),
    ("/school/modules/canteen", "Canteen", "17. Canteen & Cashless Campus"),
    ("/school/modules/courses", "Online Courses", "18. Online Courses LMS"),
    ("/school/modules/reports", "Reports Hub", "19. Reports Hub & Analytics"),
]

for path, keyword, label in routes:
    total += 1
    code, html = get(path)
    if code == 200 and keyword in html:
        print(f"PASS: {label} ({path})")
        passed += 1
    else:
        print(f"FAIL: {label} ({path}) - Code: {code}")

# Test sub-tabs
sub_tabs = [
    ("/school/modules/canteen?tab=pos", "POS", "20. Canteen POS Counter"),
    ("/school/modules/canteen?tab=wallets", "Campus Wallets", "21. Canteen Wallets"),
    ("/school/modules/labs?tab=sessions", "Practical", "22. Labs Practical Register"),
    ("/school/modules/labs?tab=incidents", "Safety", "23. Labs Safety Incidents"),
    ("/school/modules/alumni?tab=events", "Reunion", "24. Alumni Events"),
    ("/school/modules/courses?tab=courses", "Curriculum", "25. Online Courses Catalog"),
    ("/school/modules/transport?tab=tracking", "Tracking", "26. Transport Live GPS Tracking"),
    ("/school/modules/library?tab=books", "Catalog", "27. Library Books Catalog"),
    ("/school/modules/assets?tab=register", "Register", "28. Fixed Asset Register"),
]

for path, keyword, label in sub_tabs:
    total += 1
    code, html = get(path)
    if code == 200 and keyword in html:
        print(f"PASS: {label}")
        passed += 1
    else:
        print(f"FAIL: {label} ({path}) - Code: {code}")

print(f"\nModules Suite Test Result: {passed}/{total} Passed ({passed/total*100:.1f}%)")
if passed == total:
    print("ALL 19 MODULES TESTS PASSED SUCCESSFULLY!")
    sys.exit(0)
else:
    print("SOME TESTS FAILED!")
    sys.exit(1)

