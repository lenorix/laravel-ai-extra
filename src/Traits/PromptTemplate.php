<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Illuminate\Support\Facades\Blade;

trait PromptTemplate
{
    use PromptFile;

    protected function renderPrompt(string $name, array $data): string
    {
        return Blade::render($this->loadPrompt($name), $data);
    }
}
