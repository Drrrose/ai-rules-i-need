<?php

namespace Drose\LaravelAiRules\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\multiselect;

class InstallAiRulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:rules:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive setup for Laravel AI Rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Welcome to the Laravel AI Rules setup!');

        $allFiles = config('ai-rules.files', []);
        
        $options = [];
        foreach ($allFiles as $key => $config) {
            $options[$key] = $config['label'] ?? $key;
        }

        // Default selections: Boost, Gemini (instructions), and Gemini CLI (GEMINI.md)
        $defaults = ['boost', 'gemini', 'gemini_md'];

        $selected = multiselect(
            label: 'Which AI rules would you like to publish?',
            options: $options,
            default: $defaults,
            required: true,
            hint: 'Use space to select, enter to confirm.'
        );

        $this->info('Publishing selected rules...');

        $targets = [];
        foreach ($selected as $key) {
            $targets[] = $allFiles[$key]['target'];
        }

        $this->call('ai:rules', [
            '--only' => implode(',', $targets),
        ]);

        $this->info('Setup completed successfully!');
    }
}
