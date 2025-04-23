<?php

namespace Lenorix\LaravelAiExtra\Traits;

/*
 * Methods to control context limits for maltekuhr/laravel-gpt `GPTChat`.
 *
 * This expects the class has a `messages` property.
 */
trait ChatLimits
{
    /**
     * The maximum number of messages allowed in the chat.
     */
    protected int $maxMessages = 200;

    protected function ensureMessagesLimit(): void
    {
        if (count($this->messages) > $this->maxMessages) {
            $this->messages = array_slice($this->messages, -$this->maxMessages);
        }
    }
}
