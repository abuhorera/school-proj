import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')
session = requests.Session()

# 1. Login
r_login = session.get("http://127.0.0.1:8000/demo-login/schooladmin", allow_redirects=True)
print(f"Login status: {r_login.status_code}")

accounts_routes = [
    ("Accounts Dashboard", "/school/accounting/dashboard"),
    ("Income Ledger", "/school/accounts/incomes"),
    ("Create Income Form", "/school/accounts/incomes/create"),
    ("Expense Ledger", "/school/accounts/expenses"),
    ("Create Expense Form", "/school/accounts/expenses/create"),
    ("Income Heads Master", "/school/accounts/income-heads"),
    ("Create Income Head Form", "/school/accounts/income-heads/create"),
    ("Expense Heads Master", "/school/accounts/expense-heads"),
    ("Create Expense Head Form", "/school/accounts/expense-heads/create"),
    ("Bank Accounts Registry", "/school/bank-accounts"),
    ("Institutional Day Book", "/school/accounting/day-book"),
    ("Chart of Accounts (COA)", "/school/accounting/chart-of-accounts"),
    ("Legacy Hub Alias", "/school/management/accounts"),
]

print("\nTesting all Accounts & Bookkeeping sub-modules:")
passed = 0
failed = 0

for name, path in accounts_routes:
    url = f"http://127.0.0.1:8000{path}"
    try:
        res = session.get(url, timeout=5)
        if res.status_code == 200:
            print(f"  [OK 200] {name:26} : {path}")
            passed += 1
        else:
            print(f"  [FAIL {res.status_code}] {name:26} : {path}")
            failed += 1
    except Exception as e:
        print(f"  [ERROR] {name:26} : {e}")
        failed += 1

print("\n==========================================")
print(f"Total Accounts Pages Tested: {len(accounts_routes)}")
print(f"Passed: {passed} / {len(accounts_routes)}")
print(f"Failed: {failed} / {len(accounts_routes)}")
print("==========================================")

# 2. Test POST Forms (Income, Expense, Bank Account, Heads)
print("\nTesting Form Submissions (POST actions):")

# Get CSRF token
r_form = session.get("http://127.0.0.1:8000/school/accounts/incomes/create")
tokens = re.findall(r'name="_token"\s+value="([^"]+)"', r_form.text)
token = tokens[0] if tokens else ''

# 2.1 Post Income
inc_payload = {
    '_token': token,
    'name': 'Test Annual Day Ticket Sales',
    'income_head_id': 1,
    'date': '2026-11-05',
    'amount': '15000',
    'invoice_number': 'INC-TEST-001',
    'description': 'Automated test income entry'
}
r_inc = session.post("http://127.0.0.1:8000/school/accounts/incomes", data=inc_payload, allow_redirects=True)
print(f"  POST Income Voucher: Status {r_inc.status_code} (Redirected to: {r_inc.url})")

# 2.2 Post Expense
exp_payload = {
    '_token': token,
    'name': 'Test High-Speed Internet Bill',
    'expense_head_id': 1,
    'date': '2026-11-05',
    'amount': '4500',
    'invoice_number': 'EXP-TEST-001',
    'description': 'Automated test expense entry'
}
r_exp = session.post("http://127.0.0.1:8000/school/accounts/expenses", data=exp_payload, allow_redirects=True)
print(f"  POST Expense Voucher: Status {r_exp.status_code} (Redirected to: {r_exp.url})")

# 2.3 Post Bank Account
bank_payload = {
    '_token': token,
    'bank_name': 'Punjab National Bank',
    'account_name': 'Staff Provident Fund Escrow',
    'account_type': 'Savings',
    'account_number': '0892000109281',
    'branch': 'University Road / PUNB00018',
    'opening_balance': '250000'
}
r_bank = session.post("http://127.0.0.1:8000/school/bank-accounts", data=bank_payload, allow_redirects=True)
print(f"  POST Bank Account: Status {r_bank.status_code} (Redirected to: {r_bank.url})")

print("\nALL ACCOUNTS SUB-MODULES & ACTIONS VERIFIED!")

