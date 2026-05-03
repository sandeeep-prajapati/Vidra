---
name: testing-strategy
description: Implement comprehensive testing for the school management app including unit tests, feature tests, and API tests using PHPUnit. Use for ensuring code quality and preventing regressions.
---

# Testing Strategy Skill

## Testing Framework

- **PHPUnit**: Unit and feature tests
- **Test Location**: `laravel/tests/Unit/` and `laravel/tests/Feature/`
- **Database**: SQLite in-memory for testing

## Test Coverage

### Unit Tests
- Model methods and relationships
- Business logic validation
- Helper functions

### Feature Tests
- Controller actions (GET, POST, PUT, DELETE)
- Form validation
- Authentication and authorization
- Database transactions

### API Tests
- REST endpoints
- Response format validation
- Error handling
- Status codes

## Student Management Tests

Create tests for:
- Student CRUD operations
- Parent/Guardian relationships
- Previous education records
- Health records

## Best Practices

- Use database transactions (rollback after tests)
- Create factories for fake data
- Isolate tests (no dependencies)
- Test both happy path and edge cases
- Mock external services

## Goals

- 80%+ code coverage
- Fast test execution
- Continuous integration ready