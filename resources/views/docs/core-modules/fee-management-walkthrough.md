# Fee Management

Handle the full fee lifecycle — **define categories, set structures, assign fees to students, record payments, log expenses, and generate financial reports**.

---

## Features

- **Fee Categories** — group related charges (tuition, transport, hostel)
- **Discounts** — define percentage or fixed discount schemes
- **Fee Structures** — specify amount per class per category
- **Student Fees** — assign a fee structure to individual students
- **Fee Payments** — record payments against student fee accounts
- **Expenses** — log school operational costs
- **Financial Reports** — generate period-based financial summaries

---

## Fee Categories

Navigate to `/feeCategory`.

### Create a Category

1. Click **Add Category**
2. Fill in:
   - **Category Name** — e.g. `Tuition Fee`
   - **Description** — e.g. `Monthly tuition charges for all students`
3. Click **Save Category**

---

## Discounts

Navigate to `/discount`.

### Create a Discount

1. Click **Add Discount**
2. Fill in:
   - **Discount Name** — e.g. `Merit Scholarship`
   - **Discount Type** — `Percentage` or `Fixed`
   - **Discount Amount** — `15` (percent) or `500` (fixed amount)
   - **Description** — optional notes
3. Click **Save**

---

## Fee Structures

A Fee Structure defines how much a particular class is charged for a particular fee category in an academic year.

Navigate to `/feeStructure`.

### Create a Structure

1. Click **Add Structure**
2. Fill in:
   - **Academic Year** — the year this applies to
   - **Class** — which class is charged
   - **Fee Category** — which charge type
   - **Amount** — total amount due (e.g. `5000`)
   - **Due Date** — payment deadline
3. Click **Save Structure**

---

## Student Fees

Assign a fee structure to an individual student, optionally applying discounts and penalties.

Navigate to `/studentFee`.

### Assign a Fee

1. Click **Assign Fee**
2. Fill in:
   - **Student** — select the student
   - **Fee Structure** — select the applicable structure
   - **Amount Due** — amount payable
   - **Discount Amount** — any discount applied
   - **Penalty Amount** — any late payment penalty
   - **Due Date** — payment deadline for this student
3. Click **Assign Fee**

---

## Fee Payments

Record actual payments received from students.

Navigate to `/feePayment`.

### Record a Payment

1. Click **Record Payment**
2. Fill in:
   - **Student Fee** — select the student fee record
   - **Payment Date** — date payment was received
   - **Amount Paid** — amount received
   - **Payment Mode** — `Cash`, `Bank Transfer`, `Cheque`, or `Online`
   - **Transaction Reference** — cheque number or bank reference
3. Click **Save**

---

## Expenses

Log school operational expenses to track where money is spent.

Navigate to `/expense`.

### Add an Expense

1. Click **Add Expense**
2. Fill in:
   - **Expense Date** — e.g. `2026-05-10`
   - **Amount** — e.g. `12000`
   - **Category** — e.g. `Maintenance`, `Utilities`, `Salaries`
   - **Description** — e.g. `Annual maintenance of school premises`
3. Click **Save Expense**

---

## Financial Reports

Generate consolidated financial reports covering income and expenses for any period.

Navigate to `/financialReport`.

### Generate a Report

1. Click **Generate Report**
2. Select **Report Type**, **Period Start**, and **Period End**
3. Click **Generate**

The report shows total income (fee collections), total expenses, and net balance for the selected period.

---

## Recommended Setup Order

1. **Fee Categories** — define charge types
2. **Discounts** — optional scholarship schemes
3. **Fee Structures** — amounts per class per category
4. **Student Fees** — assign to students
5. **Fee Payments** — record collections
6. **Expenses** — log outgoings
7. **Financial Reports** — review net position
