# Data Transfer

**Import records from CSV files** and **export records to CSV** for students, staff, and other entities. Use Data Transfer to bulk-load data during onboarding or to extract data for reporting.

---

## Features

- **CSV Import** — bulk-insert or update records from a spreadsheet
- **Sample Download** — get a correctly formatted template for any entity
- **Export** — download current records as a CSV file
- **Validation Strategies** — control how import errors are handled

---

## Navigate to Data Transfer

Go to `/data-transfer`. The page is split into two sections: **Import** and **Export**.

---

## Import

### Step 1 — Choose Entity

Select the data type from the **Entity** dropdown (`#import-entity`):

- `student` — student records
- `staff` — staff and teacher records

### Step 2 — Download Sample File

Click the **Download Sample** link to get a pre-formatted CSV template. Fill in your data following the column headers exactly.

### Step 3 — Choose Action

Select how new rows interact with existing records:

- **append** — insert only new records; skip duplicates
- **delete** — remove existing records matching the import

### Step 4 — Validation Strategy

Choose how import errors are handled:

- **skip-errors** — continue importing valid rows; log invalid ones
- **stop-on-errors** — abort the entire import if any row fails validation

### Step 5 — Advanced Options

- **Field Separator** — CSV delimiter (`,` by default, can use `;` or tab)
- **Allowed Errors** — maximum number of errors before aborting

### Step 6 — Upload and Import

Upload your filled CSV file and click **Import**. A summary shows how many records were inserted, skipped, or errored.

---

## Export

### Step 1 — Choose Entity

Select the data type from the **Export Entity** dropdown:

- `student` — export student records
- `staff` — export staff records

### Step 2 — Apply Filters

Narrow the export before downloading:

- **Status** — e.g. `active` to export only active students
- **Date Range** — export records created within a period

### Step 3 — Download

Click **Export CSV**. Your browser downloads the file immediately.

---

## Tips

- Always download the **sample file** first — column names and order must match exactly.
- Use `skip-errors` when importing large datasets with a few bad rows.
- Use `stop-on-errors` during initial setup to ensure 100% clean data.
- Export before any major import to create a backup.
