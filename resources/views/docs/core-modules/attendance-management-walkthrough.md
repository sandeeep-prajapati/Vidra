# Attendance Management

Track **student attendance, teacher attendance, leave requests, and school holidays** from one centralized dashboard.

---

## Features

- Daily Student Attendance — mark and filter per class
- Daily Teacher Attendance — mark and filter per staff member
- Student Leave Requests — submit and approve student absences
- Teacher Leave Requests — submit and approve staff absences
- Holiday Calendar — define recurring and one-off school holidays

---

## Attendance Overview

Navigate to `/attendance-overview` to see today's at-a-glance summary.

You can view:

- **Students Today** — total marked present
- **Teachers Today** — total marked present
- **Pending & Holidays** — open leave requests and upcoming holidays

---

## Student Attendance

### Browse Records

Go to `/studentAttendance` to view all student attendance records.

Filter by **Date** (any calendar date) or **Status** (`Present`, `Absent`, `Late`, `Leave`). Click **Filter** to apply, **Reset** to clear.

### Mark Attendance

1. Click **Mark Attendance**
2. Fill in **Student** (student ID), **Date**, **Status**, **Batch**, **Marked By** (staff ID), and optional **Remarks**
3. Click **Save**

---

## Teacher Attendance

### Browse Records

Go to `/teacherAttendance` and filter by **Date** or **Status** (`Present`, `Absent`, `Late`, `Leave`).

### Mark Attendance

1. Click **Mark Attendance**
2. Fill in **Staff** (staff member ID), **Date**, **Status**, and optional **Remarks**
3. Click **Save**

---

## Student Leave Requests

### Browse Requests

Go to `/studentLeaveRequest` and filter by **Status** (`Pending`, `Approved`, `Rejected`).

### Submit a Leave Request

1. Click **Submit Leave Request** — opens `/studentLeaveRequest/create`
2. Fill in:
   - **Student** — student ID
   - **Applied On** — date of application
   - **Start Date** — first day of leave
   - **End Date** — last day of leave
   - **Status** — `Pending`, `Approved`, or `Rejected`
   - **Approved By** — approving staff member ID
   - **Reason** — reason for leave

---

## Teacher Leave Requests

### Browse Requests

Go to `/teacherLeaveRequest`. Filter by **Staff ID** and **Status**.

### Submit a Leave Request

1. Click **Submit Leave Request** — opens `/teacherLeaveRequest/create`
2. Fill the same fields as student leave, selecting **Staff** instead of Student

---

## Holiday Management

### Browse Holidays

Go to `/holiday`. Filter by **Search** (keyword) or **Recurring** (`Yes` or `No`).

### Add a Holiday

1. Click **Add Holiday**
2. Fill in:
   - **Title** — holiday name (e.g. `Annual Sports Day`)
   - **Date** — date of the holiday
   - **Description** — optional notes
   - **Recurring** — whether this repeats every year
3. Click **Save**

---

## Tips

- Mark attendance early in the day to keep records accurate.
- Use the **Recurring** flag for fixed national or school holidays.
- Leave requests show the approving staff member — make sure staff IDs are correct.
