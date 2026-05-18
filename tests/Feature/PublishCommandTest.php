<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->filesToClean = [
        '.cursorrules',
        '.clinerules',
        '.antigravityrules',
        '.windsurfrules',
        '.github/copilot-instructions.md',
        'ai-instructions.md',
        '.gemini-instructions.md',
        '.boost-rules.md',
        'CLAUDE.md',
        '.amazonq/instructions.md',
        '.aider.conf.yml',
        '.continue/instructions.md',
    ];

    foreach ($this->filesToClean as $file) {
        if (File::exists(base_path($file))) {
            File::delete(base_path($file));
        }
    }
});

afterEach(function () {
    foreach ($this->filesToClean as $file) {
        if (File::exists(base_path($file))) {
            File::delete(base_path($file));
        }
    }
});

it('can publish all ai rules files', function () {
    $this->artisan('ai:rules')
         ->assertExitCode(0);

    foreach ($this->filesToClean as $file) {
        expect(base_path($file))->toBeFile();
    }
});

it('can publish config and stubs via vendor:publish', function () {
    $this->artisan('vendor:publish', ['--tag' => 'ai-rules-config'])
         ->assertExitCode(0);
         
    expect(config_path('ai-rules.php'))->toBeFile();
    File::delete(config_path('ai-rules.php'));
    
    $this->artisan('vendor:publish', ['--tag' => 'ai-rules-stubs'])
         ->assertExitCode(0);
         
    expect(base_path('stubs/ai-rules.stub'))->toBeFile();
    File::delete(base_path('stubs/ai-rules.stub'));
});
