<?php

namespace Lenorix\LaravelAiExtra\Traits;

use MalteKuhr\LaravelGPT\Models\ChatMessage;

/*
 * Methods to control context limits for maltekuhr/laravel-gpt `GPTChat`.
 *
 * This expects the class has a `messages` property.
 */
trait ChatLimits
{
    /**
     * The maximum size of a message in bytes.
     */
    protected int $maxMessageSize = 5_000;

    /**
     * The maximum size of all messages in bytes.
     */
    protected int $maxTotalSize = 110_000;

    protected int $maxMessages = 200;

    protected function ensureMessagesLimit(): void
    {
        if (count($this->messages) > $this->maxMessages) {
            $this->messages = array_slice($this->messages, -$this->maxMessages);
        }
        $this->messages = array_filter($this->messages, function (ChatMessage $message) {
            return $this->isMessageUnderLimit($message);
        });
    }

    protected function isMessageUnderLimit(ChatMessage $message): bool
    {
        $encodedMessage = json_encode($message);

        return $encodedMessage !== false && strlen($encodedMessage) <= $this->maxMessageSize;
    }
}
