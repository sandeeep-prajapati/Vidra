---
name: performance-optimization
description: Optimize the school management app for speed and scalability. Implement caching, database optimization, query optimization, and frontend performance.
---

# Performance Optimization Skill

## Database Optimization

- **Indexing**: Add indexes on frequently queried columns
  - students.admission_number
  - parents.student_id
  - health_records.student_id

- **Eager Loading**: Use with() to prevent N+1 queries
  - Student::with(['parentInfo', 'healthRecord'])

- **Query Optimization**: Use whereBetween, whereIn for batch operations

## Caching Strategy

- **Query Cache**: Cache student lists and filters
- **Redis**: Use for session and cache storage
- **Middleware**: Cache GET requests (public pages)

## Frontend Optimization

- **Asset Bundling**: Vite.js for JS/CSS bundling
- **Lazy Loading**: Load images and components on demand
- **Compression**: Gzip for assets
- **CDN**: Serve static files from CDN

## API Optimization

- **Pagination**: Paginate large datasets (10-50 items)
- **Response Filtering**: Return only needed fields
- **Rate Limiting**: Protect against abuse
- **Compression**: Gzip JSON responses

## Monitoring

- **Query Logging**: Monitor slow queries
- **Page Speed Insights**: Measure frontend performance
- **New Relic/Datadog**: APM tools for production

## Goals

- Page load time < 2 seconds
- API response time < 500ms
- Support 10,000+ concurrent users