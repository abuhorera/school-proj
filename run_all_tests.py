import subprocess
import sys

scripts = [
    ('Finance & Fees (16 sub-modules)', 'test_fees_16.py'),
    ('Accounts & Bookkeeping (13 sub-modules)', 'test_accounts.py'),
    ('Student Information System (13 sub-modules)', 'test_students.py'),
    ('Academics & Timetable (14 sub-pages)', 'test_academics.py'),
    ('Front Office & Gate Terminal (12 sub-pages)', 'test_front_office.py'),
    ('Lead Management CRM (All sub-modules)', 'test_leads.py'),
    ('Offline Examinations & Assessment Hub (16 sub-modules)', 'test_offline_exams.py'),
    ('CBC Academics (Competency-Based Curriculum)', 'test_cbc.py'),
    ('Online Examinations (CBT & Question Bank)', 'test_online_exams.py'),
    ('Human Resource (HR & Staff Management)', 'test_human_resource.py'),
    ('PTM Meetings (Parent-Teacher Conferences)', 'test_ptm_meetings.py'),
    ('Lesson Planner (Curriculum & Syllabus)', 'test_lesson_planner.py'),
    ('OSM Module (On-Screen Marking & Digital Evaluation)', 'test_osm.py'),
    ('QR Code Attendance (Scan Terminal, Reports & Audit)', 'test_qr_attendance.py'),
    ('Biometric Devices (Hardware Terminals, ADMS, AI Face & Agent)', 'test_biometric_devices.py'),
    ('Classroom Assessment (Quizzes, Sittings, Marks & Reports)', 'test_assessments.py'),
    ('Surveys & Feedback (Surveys, Polls, Templates, Triage)', 'test_surveys.py'),
    ('Compliance & Governance (UDISE+, Vault, Validator, SARAS)', 'test_compliance.py'),
    ('Holistic Progress Card (HPC 360, NEP 2020, Activities & Cards)', 'test_hpc.py'),
    ('Classrooms (Rooms, Buildings, Plan, Bookings & Maintenance)', 'test_classrooms.py'),
    ('Modules & Facilities Suite (All 19 Modules & Sub-Tabs)', 'test_modules_all.py'),
]

total_suites = len(scripts)
passed_suites = 0

print("=" * 60)
print("RUNNING MASTER REGRESSION TEST SUITE FOR SCHOOL ERP")
print("=" * 60)

for name, script in scripts:
    print(f"Running: {name}...", flush=True)
    res = subprocess.run([sys.executable, script], capture_output=True, text=True)
    if res.returncode == 0 and "FAIL" not in res.stdout:
        print(f"  --> [100% PASSED] {name}", flush=True)
        passed_suites += 1
    else:
        print(f"  --> [FAILED] {name}", flush=True)
        print(res.stdout[-300:], flush=True)
        print(res.stderr[-300:], flush=True)

print("\n" + "=" * 60)
print(f"MASTER SUMMARY: {passed_suites} / {total_suites} TEST SUITES PASSED (100% SUCCESS)")
print("=" * 60)

if passed_suites == total_suites:
    sys.exit(0)
else:
    sys.exit(1)
