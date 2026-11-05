# school-proj

A comprehensive School Management System built on Laravel.

## Features & Modules

- **Authentication & Dashboard**: Role-aware administrative and staff portal with live metrics.
- **Academics & Classrooms**: Classes, sections, subjects, timetables, and classroom allocation.
- **Student Information**: Student enrollment, guardian profiles, houses, categories, behavior tracking, and exit records.
- **Staff & Human Resources**: Staff directory, designations, attendance, leave management, payroll, and salary templates.
- **Fee Management & Finance**: Fee groups, fee types, student fee allocation, online/offline fee collections, payment receipts, income, expenses, and bank accounts.
- **Examinations**: Offline exam scheduling, marks entry, grades, marksheets, online exams with timed attempts and question banks.
- **Attendance & Biometrics**: Daily student & staff attendance, QR Code attendance scanning, and biometric device integration with audit logging.
- **Competency-Based Curriculum (CBC)**: Pathways, tracks, strands, competencies, and student evaluation tracking.
- **Front Office & Leads**: Admission inquiries, visitor log, phone call logs, complaints, and lead followup pipelines.
- **Lesson Planner & Assessments**: Lesson planning with syllabus topics, periodic classroom assessments, and occurrences.
- **On-Screen Marking (OSM)**: Digital paper grading, evaluator allocations, annotations, and score sheets.
- **PTM & Feedback**: Parent-Teacher Meeting schedules, attendance, followups, and survey modules.
- **Compliance & Holistic Progress Cards (HPC)**: Regulatory compliance audits and CBSE/HPC progress reports.

## Tech Stack

- **Backend**: Laravel (PHP 8.x)
- **Database**: SQLite / MySQL
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js / Vanilla JS, Chart.js, FontAwesome

## Setup Instructions

1. **Clone the repository**:
   ```bash
   git clone https://github.com/abuhorera/school-proj.git
   cd school-proj
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations and seeders**:
   ```bash
   php artisan migrate --seed
   ```

5. **Start the development server**:
   ```bash
   php artisan serve
   ```
