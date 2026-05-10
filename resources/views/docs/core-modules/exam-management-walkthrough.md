# Exam Management

Create **exams, grading schemes, and schedules**, then record student marks and generate report cards — all from one connected workflow.

---

## Features

- **Exams** — define exam names, dates, and academic year
- **Grading Schemes** — map percentage ranges to letter grades
- **Exam Schedules** — schedule each subject exam on a date and time
- **Student Marks** — record individual student results
- **Report Cards** — auto-generated cards based on recorded marks

---

## Exams

Navigate to `/exam` to see all exams.

### Create an Exam

1. Click **Create Exam**
2. Fill in:
   - **Exam Name** — e.g. `Term 1 Final Examination`
   - **Academic Year** — select the year this exam belongs to
   - **Start Date** — first day of the exam period
   - **End Date** — last day of the exam period
   - **Is Final** — whether this is a terminal exam (`Yes` / `No`)
3. Click **Create Exam**

---

## Grading Schemes

Define how raw percentage marks map to letter grades and remarks.

Navigate to `/gradingScheme`.

### Add a Grade

1. Click **Add Grade**
2. Fill in:
   - **Grade** — e.g. `A+`
   - **Min Percentage** — e.g. `90`
   - **Max Percentage** — e.g. `100`
   - **Remarks** — e.g. `Outstanding`
3. Click **Save**

> Add one grade entry per band. Cover the full 0–100 range to avoid ungraded results.

---

## Exam Schedules

An Exam Schedule specifies which subject, class, date, time, and mark allocation applies to each exam slot.

Navigate to `/examSchedule`.

### Add a Schedule

1. Click **Add Schedule**
2. Fill in:
   - **Exam** — select the parent exam
   - **Class** — which class sits this exam
   - **Subject** — subject being examined
   - **Exam Date** — date of the exam
   - **Start Time** — e.g. `09:00`
   - **End Time** — e.g. `11:00`
   - **Total Marks** — maximum marks (e.g. `100`)
   - **Passing Marks** — minimum pass mark (e.g. `35`)
3. Click **Save**

---

## Student Marks

Record each student's result against an exam schedule.

Navigate to `/studentMark`.

### Add Marks

1. Click **Add Marks**
2. Fill in:
   - **Student ID** — the student being assessed
   - **Schedule** — select the relevant exam schedule
   - **Marks Obtained** — raw marks scored
   - **Grade** — letter grade (e.g. `A`)
   - **Remarks** — optional teacher notes
3. Click **Save**

---

## Report Cards

Navigate to `/studentReportCard` to view auto-generated report cards.

Report cards aggregate all student marks for an exam and display:

- Subject-wise marks and grades
- Overall percentage
- Final grade based on the grading scheme
- Teacher remarks

> Report cards are read-only — they are generated automatically from recorded marks.

---

## Recommended Setup Order

1. **Academic Year** (via Class Management)
2. **Exams** — define the exam event
3. **Grading Schemes** — set up grade bands
4. **Exam Schedules** — one entry per subject per class
5. **Student Marks** — record results after the exam
6. **Report Cards** — view generated results
