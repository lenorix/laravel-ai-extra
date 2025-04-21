<?php

namespace LaravelAiExtra\Traits;

use Illuminate\Support\Facades\File;

trait PromptFile
{
    /**
     * Load a prompt file from the resources/prompts directory.
     */
    public function loadPrompt(string $name): string
    {
        return File::get(resource_path("prompts/{$name}.txt"));
    }
}
