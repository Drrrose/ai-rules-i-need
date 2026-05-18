# Laravel AI Rules Package

A comprehensive Laravel package to standardize and empower autonomous AI coding agents (Anti-Gravity, Cursor, Windsurf, GitHub Copilot, Claude, Gemini) across your projects.

This package publishes optimized instruction sets to force AI agents to follow strict architectural guidelines, leverage real-time context via MCP servers, and adhere to industry-standard best practices.

## 🚀 Key Features

- **Scalability First**: Enforces a **scalability-first mindset** for every task. AI agents are mandated to build **scalable, enterprise-ready applications** that avoid technical debt.
- **Agent-First Ready**: Native support for **Google Anti-Gravity** instructions.
- **Real-time Context**: Full integration with **Laravel Boost** (MCP server) to allow agents to run Artisan commands, Tinker, and inspect DB schemas.
- **Architectural Excellence**: Enforces clean code patterns including Services, Actions, DTOs, Repositories, and Pipelines.
- **Spatie Standards**: Rigorous adherence to **Spatie's Laravel Guidelines**.
- **Modern Stack**: Optimized for PHP 8.3+, Pest/PHPUnit, AlpineJS, and Spatie Roles & Permissions.

## 🛠 What It Enforces

When an AI writes code for your project, this package ensures it follows:

### 0. Scalability & Future-Proofing
- **Core Mandate**: Every task must be implemented with a focus on **long-term scalability**.
- **Performance**: Use of efficient patterns (e.g., Caching, Queues, Indexing) is required by default.
- **Maintainability**: AI must prefer decoupled, modular structures over "quick fixes".

### 1. Architectural Mapping
- **Business Logic**: Extracted into **Services** and **Actions**.
- **Data Handling**: Uses **DTOs** for transfer and **Repositories** for complex fetching.
- **Validation**: Strict use of **Form Requests** matching controller structures.
- **API**: Always uses **API Resources** for formatting.
- **Filtering**: Local Scopes, Filter Classes, and Pipelines.

### 2. File & Directory Structure
- **Controllers**: `Controller/<Area>/<ModelName>/<ModelName>Controller`.
- **Requests**: `Requests/<Area>/<ModelName>/<Action>Request`.
- **Areas**: Defaults to `Panel` for administration and `Front` for public-facing logic.

### 3. Tooling & Quality
- **Laravel Boost**: Encourages agents to use `php artisan`, `tinker`, and schema inspection.
- **Static Analysis**: Enforces **PHPStan / Larastan** and **Rector / PHP Insights**.
- **Testing**: Requires **Feature**, **Unit**, and **Dusk** tests for new features.

### 4. Infrastructure & Patterns
- **Async & Scheduled**: Proper use of **Jobs**, **Queues**, and **Task Scheduler**.
- **Search & Cache**: Integration with **Laravel Scout** and the **Cache Layer**.
- **Storage**: Enforcement of the **Storage system** for all file/image data.

## 📦 Installation

Install and publish the rules in a single step:

```bash
composer config repositories.ai-rules vcs https://github.com/Drrrose/ai-rules-i-need.git && composer require --dev drose/laravel-ai-rules:dev-main && php artisan ai:rules
```

## 📖 Usage

To publish or refresh the rules:

```bash
php artisan ai:rules
```

To force overwrite existing rule files:

```bash
php artisan ai:rules --force
```

### Published Rule Files:
The command generates/updates the following files:
- `.antigravityrules` (Google Anti-Gravity)
- `.boost-rules.md` (Laravel Boost)
- `.cursorrules` (Cursor)
- `.clinerules` (Cline)
- `.windsurfrules` (Windsurf)
- `.gemini-instructions.md` (Gemini)
- `.github/copilot-instructions.md` (Copilot)
- `ai-instructions.md` (General)

## 🤝 Contributing

Contributions are welcome! Please follow the Spatie coding guidelines when submitting PRs.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
