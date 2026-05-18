<?php

return [
    /*
     * The path to the stub file containing the AI rules.
     * When you publish the stubs, it will be placed in your base_path('stubs/ai-rules.stub').
     */
    'stub_path' => base_path('stubs/ai-rules.stub'),

    /*
     * The list of rule files that should be generated.
     */
    'files' => [
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
    ],
];
