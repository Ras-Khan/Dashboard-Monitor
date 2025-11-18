# Copilot Instructions for Dashboard-Monitor

## Project Overview
This is a Symfony 7.3 web application for monitoring product prices. It uses a custom JSON-based repository for product data, with forms for adding products and a dashboard for viewing tracked items.

## Architecture
- **MVC Structure:**
  - Controllers in `src/Controller/` (e.g., `DashboardController.php`) handle routing and business logic.
  - Models (DTOs) in `src/Model/` (e.g., `Product.php`) define data structures.
  - Services in `src/Service/` (e.g., `JsonProductRepository.php`) manage data access, reading/writing to `src/Data/products.json`.
  - Forms in `src/Form/` (e.g., `ProductType.php`) define Symfony form types for product input.
  - Templates in `templates/` use Twig for rendering views.
- **Routing:** Uses attribute-based routing in controllers and is auto-configured via `config/routes.yaml`.
- **Dependency Injection:** All classes in `src/` are auto-wired as services (see `config/services.yaml`).

## Data Flow
- Product data is stored in `src/Data/products.json` and accessed via `JsonProductRepository`.
- Adding a product uses a form (`ProductType`), which is processed in the controller and saved to the JSON file.
- The dashboard view (`dashboard/index.html.twig`) displays all products from the repository.

## Developer Workflows
- **Install dependencies:**
  - `composer install`
- **Run the application (dev server):**
  - `php bin/console server:run` (or use Symfony CLI if installed)
- **Run tests:**
  - `vendor\bin\phpunit` (configured via `phpunit.dist.xml`)
- **Debugging:**
  - Use Symfony's built-in profiler and debug tools (see `config/packages/web_profiler.yaml`).
- **Add new features:**
  - Create new controllers, services, or forms in `src/` and let autowiring handle registration.

## Conventions & Patterns
- **Product data is NOT stored in a database, but in a JSON file.**
- **Forms:** Use Symfony Form component for all user input; see `ProductType.php` for field conventions.
- **Twig templates:** Use Tailwind CSS classes for styling; see `templates/dashboard/*.html.twig` for examples.
- **Flash messages:** Use `app.flashes('success')` in templates for user feedback.
- **Service construction:** Use `ParameterBagInterface` to access project parameters (e.g., for file paths).

## Integration Points
- **Symfony Bundles:** Doctrine, Twig, Monolog, Asset Mapper, Stimulus, etc. (see `composer.json`).
- **No external API calls or database integration by default.**
- **Front-end:** Uses Stimulus controllers (see `assets/controllers/`) and Tailwind CSS for UI.

## Key Files & Directories
- `src/Controller/DashboardController.php`: Main controller for dashboard and product add flows.
- `src/Service/JsonProductRepository.php`: Handles all product data operations.
- `src/Model/Product.php`: Product DTO definition.
- `src/Form/ProductType.php`: Form definition for product input.
- `src/Data/products.json`: Product data storage.
- `templates/dashboard/`: Main UI templates.
- `composer.json`: Dependency management.
- `phpunit.dist.xml`: Test configuration.

## Example Patterns
- **Add a new product:**
  - Create a new `Product` object, pass to form, validate, then save via repository.
- **Display products:**
  - Fetch all products from repository, pass to Twig template for rendering.

---
_If any section is unclear or missing, please provide feedback for further refinement._
