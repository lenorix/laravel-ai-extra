<?php

namespace Lenorix\LaravelAiExtra\Traits;

/**
 * Methods to save and load chat messages to/from the session.
 *
 * This expects the class has a `messages` property.
 */
trait ChatSession
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
