<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
     *
     * @throws FileNotFoundException
     */
    protected function renderPrompt(string $name, array $data = [], bool $deleteCached = false): string
    {
        return Blade::render(
            $this->loadPrompt($name),
            $data,
            deleteCachedView: $deleteCached
        );
    }
}
