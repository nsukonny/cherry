# Project: PHP Smarty Blog

## Overview
A lightweight blog built with PHP 8.1+, MySQL,
PDO and Smarty. No PHP frameworks.

## Architecture
- Use lightweight layered MVC.
- Follow SOLID, DRY and KISS principles.
- Use PSR-4 autoloading and PSR-12 coding style.
- Controllers handle HTTP requests.
- Repositories contain database queries.
- Smarty templates contain presentation logic only.
- Use constructor dependency injection.
- Avoid unnecessary abstractions and libraries.

## Project Structure
- src/Controller: request handling
- src/Repository: database access
- src/Http: request validation and pagination
- src/View: presentation and rendering
- templates/layouts: page layouts
- templates/components: reusable UI components
- templates/pages: page templates
- database/: schema and seed data
- public/: web entry point and assets

## Database
- Use PDO and prepared statements.
- Never concatenate external values into SQL.
- Validate sorting parameters against an allowlist.
- Avoid N+1 queries.
- Use efficient pagination.
- Articles may belong to multiple categories.

## Smarty
- Reuse components instead of duplicating markup.
- Pass data explicitly to components.
- Keep SQL and business logic out of templates.
- Escape dynamic HTML output by default.

## Coding Rules
- PHP 8.1+ compatibility.
- Use strict_types=1 in PHP files.
- Use typed properties, parameters and returns.
- Validate all external inputs.
- Handle errors explicitly.
- Do not hardcode credentials.
- Follow existing project conventions.

## Development Workflow
- Inspect existing code before introducing classes.
- Make small, focused changes.
- Do not introduce frameworks.
- Do not add abstractions without a clear benefit.
- Run relevant tests after changes.
- Keep implementation easy to explain.
- Prefer readable code over clever solutions.

## Communication
- Respond in Russian.
- Keep technical explanations concise.
- Explain architectural decisions when relevant.