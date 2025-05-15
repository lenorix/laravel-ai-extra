<?php

namespace Lenorix\LaravelAiExtra\Traits;

use MalteKuhr\LaravelGPT\Concerns\HasChat;

/**
 * Methods to save and load chat messages to/from the session.
 *
 * This expects the class has a `messages` property.
 */
trait ChatSession
{
    use HasChat;

    protected function getSessionKey(): string
    {
        return 'laravel-ai-chat-'.str_replace('\\', '-', static::class);
    }

    public function loadChat(?int $maxLatestMessages=null): void
    {
        $this->messages = session($this->getSessionKey(), []);

        if ($maxLatestMessages) {
            $this->messages = array_slice($this->messages, -$maxLatestMessages);
        }
    }

    public function saveChat(): void
    {
        session([$this->getSessionKey() => $this->messages]);
    }
}
