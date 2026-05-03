---
name: ajax-laravel-integration
description: Implement AJAX for dynamic interactions in Laravel app, including data fetching, form submissions, and updates. Use with REST APIs and queue for background processing.
---

# AJAX Laravel Integration Skill

## Usage

- Use Axios in JS for AJAX calls.
- Include CSRF token in headers.
- Handle responses, errors.
- Integrate with queue for heavy operations.

## AJAX Configuration

Configure Axios in `laravel/resources/js/app.js`:
- Set default CSRF token from meta tag
- Set base URL for API calls
- Common error handling

## Implementation Examples

- Student list with AJAX filter/search
- Form submission without page reload
- Real-time validation feedback
- Dynamic data loading with pagination

## Goals

- Improve user experience with dynamic loading.
- Reduce page reloads.
- Provide real-time feedback during operations.