<?php

namespace Drose\LaravelAiRules\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublishAiRulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:rules 
                            {--force : Overwrite existing rules files} 
                            {--only= : Comma-separated list of files to publish} 
                            {--except= : Comma-separated list of files to exclude} 
                            {--check : Check if published rules match current stubs}
                            {--dry-run : Show what would be published without making changes}
                            {--list : List all available rule targets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scalable AI coding guidelines (Laravel 13+ optimized)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allFiles = config('ai-rules.files', []);

        if ($this->option('list')) {
            $this->info('Available rule targets:');
            foreach (array_keys($allFiles) as $target) {
                $this->line("- {$target}");
            }
            return 0;
        }

        $placeholders = $this->resolvePlaceholders();
        
        $only = $this->option('only') ? explode(',', $this->option('only')) : [];
        $except = $this->option('except') ? explode(',', $this->option('except')) : [];
        
        $filesToProcess = collect($allFiles)->filter(function ($config, $key) use ($only, $except) {
            $target = $config['target'] ?? $key;
            $matchesOnly = empty($only) || in_array($target, $only) || in_array(basename($target), $only);
            $matchesExcept = !empty($except) && (in_array($target, $except) || in_array(basename($target), $except));
            return $matchesOnly && !$matchesExcept;
        });

        if ($filesToProcess->isEmpty()) {
            $this->warn('No files matched the specified criteria.');
            return 0;
        }

        $needsUpdate = false;
        $stubPath = config('ai-rules.stub_path', base_path('stubs/ai-rules.stub'));

        foreach ($filesToProcess as $key => $config) {
            $target = $config['target'] ?? $key;
            $templatePath = $config['template'] ?? $stubPath;

            if (!File::exists($templatePath)) {
                // Fallback to package stub if base_path one doesn't exist
                $templatePath = __DIR__ . '/../../../stubs/' . basename($templatePath);
            }

            if (!File::exists($templatePath)) {
                $this->error("Template not found for {$target}: {$templatePath}");
                continue;
            }

            $content = $this->replacePlaceholders(File::get($templatePath), $placeholders);
            $fullPath = base_path($target);

            if ($this->option('check')) {
                if (!File::exists($fullPath) || File::get($fullPath) !== $content) {
                    $this->warn("File {$target} is out of date or missing.");
                    $needsUpdate = true;
                }
                continue;
            }

            if ($this->option('dry-run')) {
                $status = File::exists($fullPath) ? 'would update' : 'would create';
                $this->info("[Dry Run] {$status}: {$target}");
                continue;
            }

            $dir = dirname($fullPath);
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            if (File::exists($fullPath) && !$this->option('force') && File::get($fullPath) !== $content) {
                $this->warn("File {$target} already exists and differs. Use --force to overwrite.");
                continue;
            }

            if (!File::exists($fullPath) || File::get($fullPath) !== $content) {
                File::put($fullPath, $content);
                $this->info("Published AI rules to: {$target}");
            } else {
                $this->line("File {$target} is already up to date.");
            }
        }

        if ($this->option('check')) {
            return $needsUpdate ? 1 : 0;
        }

        return 0;
    }

    /**
     * Resolve project-specific placeholders.
     */
    protected function resolvePlaceholders()
    {
        $laravel = app();
        
        return array_merge([
            'project_name' => config('app.name', 'Laravel'),
            'laravel_version' => $laravel->version(),
            'php_version' => PHP_VERSION,
            'test_runner' => $this->detectTestRunner(),
            'installed_packages' => $this->getInstalledPackages(),
        ], config('ai-rules.placeholders', []));
    }

    /**
     * Replace placeholders in content.
     */
    protected function replacePlaceholders($content, $placeholders)
    {
        foreach ($placeholders as $key => $value) {
            $content = str_replace("{{ {$key} }}", $value, $content);
        }

        return $content;
    }

    /**
     * Detect if Pest or PHPUnit is used.
     */
    protected function detectTestRunner()
    {
        if (File::exists(base_path('tests/Pest.php'))) {
            return 'Pest';
        }

        return 'PHPUnit';
    }

    /**
     * Get a list of key installed packages.
     */
    protected function getInstalledPackages()
    {
        $composerFile = base_path('composer.json');
        if (!File::exists($composerFile)) {
            return 'Unknown';
        }

        $composer = json_decode(File::get($composerFile), true);
        $packages = array_merge(
            $composer['require'] ?? [],
            $composer['require-dev'] ?? []
        );

        $interesting = [
            'laravel/framework',
            'laravel/boost',
            'spatie/guidelines-skills',
            'inertiajs/inertia-laravel',
            'livewire/livewire',
            'laravel/jetstream',
            'laravel/breeze',
        ];

        return collect($packages)
            ->filter(fn ($v, $k) => in_array($k, $interesting))
            ->keys()
            ->implode(', ');
    }
}
