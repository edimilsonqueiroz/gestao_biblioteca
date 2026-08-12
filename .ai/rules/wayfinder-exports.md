# Wayfinder Exports Pattern

**File:** `resources/js/routes/librarian/index.ts`

**Rule:** Always export Wayfinder route modules using namespace exports.

## Correct Format

```typescript
export * as books from './books'
export * as loans from './loans'
export * as reservations from './reservations'
```

## Incorrect Format (DO NOT USE)

```typescript
import books from './books'
import loans from './loans'
const librarian = {
    books: Object.assign(books, books),
    loans: Object.assign(loans, loans),
}
export default librarian
```

## Note

This file is regenerated when `php artisan wayfinder:generate` is run. If it reverts to the incorrect format, fix it immediately using the correct namespace export pattern above.
