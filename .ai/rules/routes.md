---
paths:
  - 'resources/js/routes/**'
---

# Routes

## Wayfinder Namespace Exports
Always use `export * as moduleName from './module'` pattern in index files. Never use `Object.assign` or default imports. Wayfinder's generate command may revert this; always check and fix if needed.
