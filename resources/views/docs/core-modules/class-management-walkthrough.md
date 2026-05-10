# Class Management

Organise your school's academic structure by managing **Academic Years, Classes, Sections, and Batches**.

---

## Features

- **Academic Years** — define school year ranges and dates
- **Classes** — create grade levels (e.g. Grade 1, Grade 10)
- **Sections** — divide classes into smaller groups (A, B, C)
- **Batches** — link a class + section to an academic year

---

## Academic Years

Navigate to `/academic-years` to see all configured academic years.

### Create an Academic Year

1. Click **Add Academic Year**
2. Fill in:
   - **Year Range** — label for the year (e.g. `2026 Batch`)
   - **Start Date** — first day of the academic year
   - **End Date** — last day of the academic year
3. Click **Create Academic Year**

> Academic years are referenced by fee structures, exams, curriculum, and timetables. Create them first.

---

## Classes

Navigate to `/classes` to browse all classes.

### Create a Class

1. Click **Add Class**
2. Fill in:
   - **Class Name** — human-readable name (e.g. `Grade 5`)
   - **Class Code** — short identifier (e.g. `CLS-G5`)
3. Click **Create Class**

---

## Sections

Sections divide a class into parallel groups — useful for large schools where one class has multiple teachers.

Navigate to `/sections`.

### Create a Section

1. Click **Add Section**
2. Fill in:
   - **Section Name** — label (e.g. `Section A`)
   - **Capacity** — maximum number of students
   - **Class** — select the parent class
3. Click **Create Section**

---

## Batches

A **Batch** combines a Class + Section + Academic Year into a single schedulable unit. All timetables, attendance, and exams reference batches.

Navigate to `/batches`.

### Create a Batch

1. Click **Add Batch**
2. Fill in:
   - **Batch Name** — label (e.g. `2026 Batch`)
   - **Class** — select the class
   - **Section** — select the section
3. Click **Create Batch**

---

## Recommended Setup Order

1. **Academic Year** — defines the school calendar
2. **Classes** — grade levels
3. **Sections** — parallel groups within each class
4. **Batches** — bind them all together

Following this order ensures all dependent modules have the data they need.
