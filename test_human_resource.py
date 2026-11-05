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
    print(" TESTING HUMAN RESOURCE (HR & STAFF MANAGEMENT)   ")
    print("==================================================")

    routes = [
        ("/school/staff", "Staff Directory Master"),
        ("/school/staff/create", "Add Staff Onboarding Form"),
        ("/school/staff-attendance", "Staff Attendance Register"),
        ("/school/payroll", "Monthly Payroll Hub"),
        ("/school/staff-salary", "Set Salary / Compensation Roster"),
        ("/school/salary-templates", "Salary Structure Templates"),
        ("/school/approve-leave", "Staff Leave Requests & Approvals"),
        ("/school/leave-types", "Leave Policy & Entitlement Types"),
        ("/school/departments", "Institutional Departments Catalog"),
        ("/school/designations", "Job Roles & Designations"),
        ("/school/loans", "Staff Loans & Salary Advance Ledger"),
        ("/school/staff-id-card", "Staff Identity Card Badges"),
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
    post_total = 7

    if not csrf_token:
        req = urllib.request.Request(BASE_URL + "/school/departments", headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        csrf_token = get_csrf(res.read().decode('utf-8', errors='ignore'))

    # POST 1: Add Department
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'name': 'Robotics & AI Lab',
            'description': 'Advanced computational thinking and automation laboratory unit.'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/departments", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/departments (Add Department)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/departments")
    except Exception as e:
        print(f"[ERROR] POST /school/departments: {e}")

    # POST 2: Add Designation
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'name': 'AI Curriculum Specialist',
            'description': 'Faculty leader for digital technology and STEM programs.'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/designations", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/designations (Add Designation)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/designations")
    except Exception as e:
        print(f"[ERROR] POST /school/designations: {e}")

    # POST 3: Add Leave Type
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'name': 'Paternity Leave',
            'type': 'Paid',
            'yearly_quota': 15,
            'accrual_frequency': 'Yearly',
            'carry_forward': '1',
            'description': 'Special leave entitlement for new fathers.'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/leave-types", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/leave-types (Add Leave Type)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/leave-types")
    except Exception as e:
        print(f"[ERROR] POST /school/leave-types: {e}")

    # POST 4: Add Salary Template
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'name': 'Senior Faculty Grade IV',
            'basic_salary': 45000,
            'hra': 18000,
            'da': 9000,
            'conveyance': 4000,
            'medical_allowance': 3000,
            'epf_deduction': 3500,
            'esi_deduction': 800,
            'professional_tax': 200
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/salary-templates", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/salary-templates (Create Salary Template)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/salary-templates")
    except Exception as e:
        print(f"[ERROR] POST /school/salary-templates: {e}")

    # POST 5: Mark Daily Attendance
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'date': '2026-11-05',
            'attendance[1][status]': 'P',
            'attendance[1][in_time]': '08:00',
            'attendance[1][out_time]': '16:00',
            'attendance[1][remarks]': 'Automated attendance check',
            'attendance[2][status]': 'P',
            'attendance[2][in_time]': '08:05',
            'attendance[2][out_time]': '16:00',
            'attendance[2][remarks]': 'Automated attendance check'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/staff-attendance", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/staff-attendance (Save Daily Attendance)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/staff-attendance")
    except Exception as e:
        print(f"[ERROR] POST /school/staff-attendance: {e}")

    # POST 6: Issue Staff Loan
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'staff_id': 1,
            'loan_amount': 25000,
            'term_months': 5,
            'reason': 'Emergency medical advance'
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/loans", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/loans (Issue Staff Loan)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/loans")
    except Exception as e:
        print(f"[ERROR] POST /school/loans: {e}")

    # POST 7: Generate Monthly Payroll
    try:
        data = urllib.parse.urlencode({
            '_token': csrf_token,
            'month': 'December',
            'year': 2026
        }).encode('utf-8')
        req = urllib.request.Request(BASE_URL + "/school/payroll/generate", data=data, headers={'User-Agent': 'Mozilla/5.0'})
        res = opener.open(req)
        if res.getcode() in [200, 302]:
            print("[OK] POST /school/payroll/generate (Generate Monthly Payroll)")
            post_passed += 1
        else:
            print(f"[FAIL {res.getcode()}] POST /school/payroll/generate")
    except Exception as e:
        print(f"[ERROR] POST /school/payroll/generate: {e}")

    print(f"\nPOST Actions: {post_passed}/{post_total} PASSED")

    total_score = (passed_count + post_passed)
    max_score = (total_count + post_total)
    print(f"\nOVERALL RESULT: {total_score}/{max_score} tests passed.")

    if total_score == max_score:
        print("ALL HUMAN RESOURCE TESTS COMPLETED SUCCESSFULLY! (100% SUCCESS)")
        sys.exit(0)
    else:
        print("SOME TESTS FAILED! Check outputs above.")
        sys.exit(1)

if __name__ == '__main__':
    main()
