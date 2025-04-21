<?php

namespace Lenorix\LaravelAiExtra\Traits;

trait ChatLimits
{
    /**
     * The maximum number of messages allowed in the chat.
     *
     * @var int
     */
    protected int $maxMessages = 200;

    protected function ensureMessagesLimit(): void
    {
        if (count($this->messages) > $this->maxMessages) {
            $this->messages = array_slice($this->messages, -$this->maxMessages);
        }
    }
}
