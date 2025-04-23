<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Illuminate\Support\Facades\File;

/**
 * Methods to load prompt files from the resources/prompts directory.
 */
trait PromptFile
{
    /**
     * Load a prompt file from the resources/prompts directory.
     */
    protected function loadPrompt(string $name): string
    {
        return File::get(resource_path("prompts/{$name}.md"));
    }
}
