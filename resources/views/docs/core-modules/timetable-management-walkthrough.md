# Timetable Management

Build and manage **class schedules, rooms, teaching periods, substitute assignments, and special events** in one unified system.

---

## Features

- **Timetable View** — grid view of classes by period and day
- **Rooms** — manage classrooms, labs, and halls
- **Periods** — define time slots for each teaching period
- **Days** — configure the school week structure
- **Timetable Entries** — assign subjects and teachers to slots
- **Substitute Assignments** — record teacher substitutions
- **Special Events** — schedule one-off room bookings and events

---

## Timetable View

Navigate to `/timetable` to see the class timetable grid.

Use URL parameters to filter the view:

```
/timetable?class_id=1&section_id=1&academic_year_id=1
```

The grid shows rows for Periods, columns for Days, and cells containing the assigned Subject and Teacher.

---

## Rooms

Rooms are physical spaces where classes and events are held.

Navigate to `/room`.

### Add a Room

1. Click **Add Room**
2. Fill in:
   - **Room Name** — e.g. `Science Lab 1`
   - **Room Type** — `Classroom`, `Lab`, `Hall`, or `Library`
   - **Capacity** — maximum occupancy
   - **Description** — optional notes
3. Click **Save Room**

---

## Periods

Periods define the time slots within a school day.

Navigate to `/period`.

### Add a Period

1. Click **Add Period**
2. Fill in **Start Time** (e.g. `09:00`) and **End Time** (e.g. `09:45`)
3. Click **Save Period**

> Add all daily periods in order. The timetable grid rows correspond to periods.

---

## Days

Days represent the working days of the school week.

Navigate to `/day`.

### Add a Day

1. Click **Add Day**
2. Fill in **Day Name** (e.g. `Monday` or a custom label)
3. Click **Save Day**

---

## Timetable Entries

A timetable entry places a subject + teacher into a specific class/section/period/day slot.

Navigate to `/timetable/create`.

### Add an Entry

1. Select:
   - **Class**, **Section**, **Academic Year**
   - **Day** — which day of the week
   - **Period** — which time slot
   - **Subject** — subject being taught
   - **Teacher** — teacher delivering the lesson
   - **Room** — room where it takes place
2. Click **Save Entry**

---

## Substitute Assignments

Record when a teacher is absent and another covers their slot.

Navigate to `/substituteAssignment`.

### Add a Substitution

1. Click **Add Substitution**
2. Fill in:
   - **Timetable Entry** — which scheduled slot is being covered
   - **Original Teacher** — the absent teacher
   - **Substitute Teacher** — the covering teacher
   - **Date of Substitution** — the specific date
3. Click **Save Substitution**

---

## Special Events

Special Events are one-off room bookings for assemblies, parent meetings, sports days, etc.

Navigate to `/specialEvent`.

### Add an Event

1. Click **Add Event**
2. Fill in:
   - **Event Name** — e.g. `Documentation Planning Meeting`
   - **Event Date** — e.g. `2026-05-08`
   - **Start Time** and **End Time**
   - **Room** — select the room
   - **Description** — purpose of the event
3. Click **Save Event**

---

## Recommended Setup Order

1. **Rooms** — register all physical spaces
2. **Days** — configure the school week
3. **Periods** — set up daily time slots
4. **Timetable Entries** — populate the schedule grid
5. **Substitute Assignments** — record cover as needed
6. **Special Events** — schedule one-off events
