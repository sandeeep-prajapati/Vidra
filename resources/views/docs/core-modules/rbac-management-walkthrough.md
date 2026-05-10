# RBAC Management

Control **who can do what** in OpenVidra using Roles, Permissions, and User Role Assignments. The Role-Based Access Control (RBAC) system lets you define fine-grained access policies without touching code.

---

## Features

- **Roles** — group permissions into named access levels
- **Permissions** — define individual allowed actions
- **Role-Permission Matrix** — toggle which permissions belong to which role
- **User Role Assignment** — grant roles to specific user accounts
- **User Management** — create and manage user accounts

---

## Roles

A **Role** is a named collection of permissions (e.g. `Teacher`, `Accountant`, `Librarian`).

Navigate to `/roles`.

### Create a Role

1. Click **New Role**
2. Fill in:
   - **Name** — e.g. `doc-role`
   - **Description** — e.g. `Role created for the RBAC documentation walkthrough`
3. Click **Create Role**

---

## Permissions

A **Permission** is a single granular action (e.g. `view-students`, `edit-fees`, `delete-reports`).

Navigate to `/permissions`.

### Create a Permission

1. Click **New Permission**
2. Fill in:
   - **Name** — e.g. `view-doc`
3. Click **Create Permission**

> Use a consistent naming pattern: `{action}-{resource}` (e.g. `create-exams`, `view-reports`).

---

## Role-Permission Matrix

The matrix lets you toggle which permissions are assigned to each role without navigating into individual role pages.

Navigate to `/role-permissions`.

The matrix displays roles and permissions side by side. Click the **Admin** or **Edit** button for a role to open its permission assignment form. Toggle the permissions on or off and save.

---

## User Role Assignment

Navigate to `/user-roles` to see all user → role mappings.

Assign a role to a user to immediately grant them all permissions that role includes.

---

## User Management

Create user accounts that can log in to OpenVidra.

### Create a User

1. From `/user-roles`, click **+ Create User**
2. Fill in:
   - **Name** — full display name
   - **Email** — login email address
   - **Password** — secure password
   - **Confirm Password** — repeat for verification
3. Click **Create**

After creation, go to **User Role Assignment** and assign the new user one or more roles.

---

## Recommended Setup Order

1. **Permissions** — define all allowed actions
2. **Roles** — create role groups
3. **Role-Permission Matrix** — assign permissions to roles
4. **Users** — create user accounts
5. **User Role Assignment** — link users to roles

---

## Tips

- Keep role names aligned to job functions, not people.
- Avoid giving the same permission to too many roles — it makes auditing harder.
- Review the matrix periodically to remove permissions that are no longer needed.
- Use a `viewer` role for read-only access and build upward.
