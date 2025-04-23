<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Illuminate\Support\Facades\Blade;

/**
 * Methods to render prompt files with Blade templating.
 */
trait PromptTemplate
{
    use PromptFile;

    /**
     * Render a prompt file with the given data.
     *
     * @param  string  $name  The name of the prompt file (without extension).
     * @param  array  $data  The data to pass to the prompt.
     * @return string
     */
    protected function renderPrompt(string $name, array $data): string
    {
        return Blade::render($this->loadPrompt($name), $data);
    }
}
