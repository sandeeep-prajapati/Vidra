# Hostel & Transport Management

Manage **residential accommodation and school transport services** — from hostel buildings and rooms to bus routes and student assignments.

---

## Features

- **Hostels** — register hostel buildings with type and capacity
- **Hostel Rooms** — add rooms within each hostel
- **Hostel Assignments** — assign students to hostel rooms
- **Transportation** — register bus/van routes with capacity
- **Transport Assignments** — assign students to transport services

---

## Hostels

Navigate to `/hostels` to see all hostel buildings.

### Add a Hostel

1. Click **Add Hostel**
2. Fill in:
   - **Hostel Name** — e.g. `Boys Block A`
   - **Hostel Type** — `Boys` or `Girls`
   - **Total Capacity** — total beds available
   - **Available Capacity** — current free beds
   - **Location** — building location on campus
3. Click **Create Hostel**

---

## Hostel Rooms

Each hostel contains multiple rooms with individual capacity and type.

Navigate to `/hostel-rooms`.

### Add a Room

1. Click **Add Room**
2. Fill in:
   - **Hostel** — select the parent hostel
   - **Room Number** — e.g. `R-101`
   - **Room Type** — `Single`, `Double`, `Triple`, or `Dormitory`
   - **Capacity** — maximum occupants
   - **Occupied** — current number of occupants
3. Click **Create Room**

---

## Hostel Assignments

Assign a student to a specific room for the duration of the academic year.

Navigate to `/student-hostels`.

### Assign a Student

1. Click **Assign Student**
2. Fill in:
   - **Student ID** — the student being assigned
   - **Hostel** — select the hostel
   - **Room** — select the room within that hostel
   - **Assigned Date** — move-in date
   - **Checkout Date** — expected checkout date
3. Click **Create Assignment**

> The room's occupied count updates automatically when students are assigned.

---

## Transportation

Register school transport services — buses, vans, or any vehicles used for student transport.

Navigate to `/transportation`.

### Add a Transport Service

1. Click **Add Transport**
2. Fill in:
   - **Transport Name** — e.g. `Bus Route 3 — City Centre`
   - **Transport Type** — `Bus`, `Van`, or `Car`
   - **Capacity** — maximum passengers
   - **Departure Time** — e.g. `07:30`
   - **Route** — route description (e.g. `City Centre → School Gate`)
3. Click **Create Transport**

---

## Transport Assignments

Navigate to `/student-transport` to view all student transport assignments.

Click **Assign Student** and select the student and their transport route to create an assignment.

---

## Tips

- Set **Available Capacity** accurately when creating hostels — it drives room availability checks.
- Use the **Checkout Date** field to automatically flag room vacancies at year-end.
- Group transport routes by direction (morning/evening) and label them clearly in the **Route** field.
