<?php

namespace Lenorix\LaravelAiExtra\Traits;

trait ChatInSession
{
    protected function getSessionKey(): string
    {
        return 'laravel-ai-chat-'.str_replace('\\', '-', static::class);
    }

    public function loadChat(): void
    {
        $this->messages = session($this->getSessionKey(), []);
    }

    public function saveChat(): void
    {
        session([$this->getSessionKey() => $this->messages]);
    }
}
