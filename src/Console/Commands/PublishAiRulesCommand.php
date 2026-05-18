<?php

namespace Drose\LaravelAiRules\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishAiRulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:rules {--force : Overwrite existing rules files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish AI coding guidelines (for Anti-Gravity, Claude, Gemini, Cursor) for this Laravel project';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rules = <<<EOT
You are an expert Laravel developer and an autonomous AI agent. When writing code or performing tasks for this project, you must strictly adhere to the following rules:

1. **Scalability & Enterprise Readiness (CORE MANDATE)**:
   - **Focus on building SCALABLE projects.** Every task assigned to you must be implemented with a **scalability-first mindset**.
   - Ensure the architecture is designed to handle growth in both data volume and business logic complexity.
   - Avoid "quick fixes" that create technical debt; always prefer patterns that support long-term maintainability and performance.

2. **Autonomous Agency & Tooling (Anti-Gravity & Laravel Boost)**:
   - You are operating in an agent-first environment (Google Anti-Gravity). 
   - ALWAYS leverage the **Laravel Boost MCP server** to gain real-time context.
   - You have permission to:
     - Run `php artisan` commands to inspect the state or generate code.
     - Execute code snippets via `Tinker` to verify logic.
     - Inspect the database schema and run SQL queries to debug data issues.
     - Search the vectorized Laravel documentation provided by Boost.
   - Before suggesting a fix for a bug, use these tools to reproduce the issue or verify the current state.

3. **Spatie Guidelines & Code Quality**:
   - Follow **Spatie's Laravel Guidelines** rigorously.
   - Use strict typing (property types, return types, argument types).
   - Prefer **Constructor Promotion** and **Dependency Injection** via the **Service Container**.
   - Use early returns to reduce nesting.
   - Ensure all new features are accompanied by corresponding tests (Pest or PHPUnit).
   - Static analysis: Use **PHPStan / Larastan**, **Rector / PHP Insights**, and **Monitoring Services** for health checks.

4. **Architectural Mapping & Pattern Enforcement**:
   - **Validation**: ALWAYS use **Form Requests**. For reusable validation, use **Rule Objects** or **Custom Validation Rules**.
   - **Business Logic**: Extract complex logic into **Services**. Use **Actions** for single-purpose operations.
   - **Data Transformation**: Use **DTOs** (Data Transfer Objects) for passing data between layers.
   - **Database Abstraction**: Use **Repositories** for complex data fetching.
   - **Query Filtering**: Use **Local Scopes** / **Query Scopes** for simple filters, **Filter Classes** or **Pipelines** for complex ones.
   - **API Responses**: Always use **API Resources** for formatting.
   - **Authorization**: Use **Policies** for resource-specific logic and **Gates** for simple rules. Use **Spatie Roles & Permissions** for access management.
   - **Model Behavior**: Use **Traits** for reusable behavior, **Accessors & Mutators** for attribute transformation, and **Enums** for constants.
   - **Events & Lifecycle**: Use **Observers** for model lifecycle hooks and **Events/Listeners** for decoupled logic.
   - **Infrastructure**: Use **Jobs** for background processing, **Queues** for async tasks, and **Task Scheduler** for scheduled operations.
   - **Caching & Search**: Use **Cache Layer** for performance and **Scout** (with dedicated drivers) for search indexing.
   - **Error Handling & Logging**: Use the **Exception Handler**, **Custom Exceptions**, and dedicated **Log Channels**.
   - **Third-Party Integration**: Use **Dedicated Service Classes** (e.g., **Payment Services**) or **Domain Services**.
   - **Frontend & UI**: Use **Blade Components** for reusable UI, **View Models** for presentation formatting, and **AlpineJS** for frontend state.
   - **Storage**: Any image path or storage data MUST use the **Storage system** (uploadable image).
   - **Multi-tenancy**: Use **Tenant Services** or **Middleware** for separation.

5. **Routing & Middleware**:
   - Organize routes using **Route Groups**.
   - Use **Route Model Binding** for data resolution.
   - Use **Middleware** for request sanitization, **CSRF protection**, **Guards** for authentication, and **Rate Limiters/Throttles**.
   - Use **Response Macros** for reusable responses.

6. **Configuration & Utilities**:
   - Manage application state via **Config Files** and **.env** for environment variables.
   - Use **Helpers** for shared utility functions.
   - Use **Shared Constants** via Enums or Config.
   - Input normalization via **Custom Request Classes**.

7. **Advanced Patterns & Workflows**:
   - Use **Pipelines** for complex workflows or multi-step operations.
   - Use **Transactions** for atomic operations.
   - Use **Pagination Classes** for data pagination.
   - Implement **Localization** via Lang Files (prefer "macamcara" approach).
   - Implement **Audit tracking** via Observers/Events.

8. **Controller Structure & Naming**: 
   - Controllers MUST follow this strict directory and namespace structure: `Controller/<Area>/<ModelName>/<ModelName>Controller`.
   - Usually, <Area> is `Panel` (for admin/dashboard) or `Front` (for public-facing).
   - Example: `app/Http/Controllers/Panel/Tasks/TasksController.php`.
   - **Form Requests** must follow the same path: `app/Http/Requests/Panel/Tasks/StoreTaskRequest.php`.

9. **Testing Strategy**:
   - **Feature Tests** & **HTTP Tests**: For APIs and high-level functionality.
   - **Unit Tests**: For isolated logic.
   - **Browser Testing**: Use **Laravel Dusk**.

Follow these instructions for every single response or code generation within this project.
EOT;

        $files = [
            '.cursorrules' => $rules,
            '.clinerules' => $rules,
            '.antigravityrules' => $rules,
            '.windsurfrules' => $rules,
            '.github/copilot-instructions.md' => $rules,
            'ai-instructions.md' => $rules,
            '.gemini-instructions.md' => $rules,
            '.boost-rules.md' => $rules,
        ];

        foreach ($files as $path => $content) {
            $fullPath = base_path($path);
            
            $dir = dirname($fullPath);
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            if (File::exists($fullPath) && !$this->option('force')) {
                $this->warn("File {$path} already exists. Use --force to overwrite.");
                continue;
            }

            File::put($fullPath, $content);
            $this->info("Published AI instructions to: {$path}");
        }

        $this->info("AI rules have been successfully published! Claude, Gemini, and Codex will now follow these guidelines.");
    }
}
