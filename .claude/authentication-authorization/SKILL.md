---
name: authentication-authorization
description: Implement authentication and role-based access control for the school management app. Use for securing endpoints and managing user permissions across different roles.
---

# Authentication and Authorization Skill

## Authentication System

- **Method**: Laravel Sanctum for API authentication
- **User Model**: Extend User model in `app/Models/User.php`
- **Login**: REST endpoint for token generation
- **Token**: Bearer token in Authorization header

## Roles and Permissions

### User Roles

1. **Admin** - Full access to all modules
2. **Principal** - School-wide oversight
3. **Teacher** - Class/subject management
4. **Student** - View own records
5. **Parent** - View child's records
6. **Accountant** - Finance module
7. **Librarian** - Library management
8. **Staff** - Administrative tasks

## Permission Gates

- View students (teacher, admin, parent of student)
- Edit students (teacher, admin)
- Delete students (admin)
- View finances (accountant, admin)
- Edit timetable (admin, principal)

## Implementation

- Create roles and permissions tables
- Use policies for resource authorization
- Middleware for route protection
- API middleware for token verification

## Student Management Access

- Students: View own details
- Parents: View child's details
- Teachers: View class students
- Admin: Full access

## Goals

- Secure all endpoints
- Implement principle of least privilege
- Audit logs for sensitive operations
- Rate limiting on API