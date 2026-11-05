import requests

session = requests.Session()
# Login via demo login
r_login = session.get("http://127.0.0.1:8000/demo-login/schooladmin", allow_redirects=True)
print(f"Login status: {r_login.status_code}")

fees_routes = [
    ("Fees Dashboard", "/school/fees-dashboard"),
    ("Collect Fees (POS)", "/school/collect-fees"),
    ("Search Due Fees", "/school/due-fees"),
    ("All Transactions", "/school/transactions"),
    ("Online Transactions", "/school/online-payments"),
    ("Fee Challans", "/school/fee-challans"),
    ("Assign Fees", "/school/assign-fees"),
    ("Fees Carry Forward", "/school/fees-carry-forward"),
    ("Fee Groups", "/school/fee-groups"),
    ("Fees Discount", "/school/fee-discounts"),
    ("Fee Types", "/school/fee-types"),
    ("Generate Due Slip", "/school/due-slips"),
    ("Due Slip History", "/school/due-slips/history"),
    ("Fee Data Audit", "/school/fee-audit"),
    ("Import Center (NEW)", "/school/fee-imports"),
    ("Late Fine Rules (NEW)", "/school/fee-fine-rules")
]

print("\nTesting all 16 Finance & Fees sub-modules:")
passed = 0
failed = 0

for name, path in fees_routes:
    url = f"http://127.0.0.1:8000{path}"
    try:
        res = session.get(url, timeout=5)
        if res.status_code == 200:
            passed += 1
            print(f"  [OK 200] {name.ljust(25)} : {path}")
        else:
            failed += 1
            print(f"  [FAIL {res.status_code}] {name.ljust(25)} : {path}")
    except Exception as e:
        failed += 1
        print(f"  [ERROR] {name.ljust(25)} : {path} -> {e}")

print(f"\n==========================================")
print(f"Total Fees Pages Tested: {len(fees_routes)}")
print(f"Passed: {passed} / {len(fees_routes)}")
print(f"Failed: {failed} / {len(fees_routes)}")
print(f"==========================================")

if failed == 0:
    print("\nALL 16 FINANCE & FEES PAGES RETURN 200 OK CLEANLY!")

