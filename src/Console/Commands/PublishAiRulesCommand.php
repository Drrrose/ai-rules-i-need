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
    protected $signature = 'ai:rules {--force : Overwrite existing rules files} {--only= : Comma-separated list of files to publish} {--except= : Comma-separated list of files to exclude} {--check : Check if published rules match the current stub without modifying}';

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
        $stubPath = config('ai-rules.stub_path', base_path('stubs/ai-rules.stub'));
        if (!File::exists($stubPath)) {
            $stubPath = __DIR__ . '/../../../stubs/ai-rules.stub';
        }

        if (!File::exists($stubPath)) {
            $this->error("Stub file not found at {$stubPath}");
            return 1;
        }

        $rules = File::get($stubPath);
        $allFiles = config('ai-rules.files', []);
        
        $only = $this->option('only') ? explode(',', $this->option('only')) : [];
        $except = $this->option('except') ? explode(',', $this->option('except')) : [];
        
        $filesToProcess = collect($allFiles)->filter(function ($file) use ($only, $except) {
            $matchesOnly = empty($only) || in_array($file, $only) || in_array(basename($file), $only);
            $matchesExcept = !empty($except) && (in_array($file, $except) || in_array(basename($file), $except));
            return $matchesOnly && !$matchesExcept;
        })->toArray();

        $needsUpdate = false;

        foreach ($filesToProcess as $path) {
            $fullPath = base_path($path);
            
            if ($this->option('check')) {
                if (!File::exists($fullPath) || File::get($fullPath) !== $rules) {
                    $this->warn("File {$path} is out of date or missing.");
                    $needsUpdate = true;
                }
                continue;
            }

            $dir = dirname($fullPath);
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            if (File::exists($fullPath) && !$this->option('force') && File::get($fullPath) !== $rules) {
                $this->warn("File {$path} already exists and differs from the stub. Use --force to overwrite.");
                continue;
            }

            if (!File::exists($fullPath) || File::get($fullPath) !== $rules) {
                File::put($fullPath, $rules);
                $this->info("Published AI instructions to: {$path}");
            } else {
                $this->line("File {$path} is already up to date.");
            }
        }

        if ($this->option('check')) {
            if ($needsUpdate) {
                $this->error("Some AI rules files are out of date. Run 'php artisan ai:rules --force' to update them.");
                return 1;
            } else {
                $this->info("All AI rules files are up to date.");
            }
        } else {
            $this->info("AI rules processing completed successfully!");
        }

        return 0;
    }
}
