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

        $total = 0;
        $newMessages = [];
        while (! empty($this->messages)) {
            $message = array_pop($this->messages);
            $encoded = json_encode($message);

            if ($encoded === false) {
                array_unshift($newMessages, $message);
                unset($encoded);

                continue;
            }

            $size = strlen($encoded);
            unset($encoded);
            if ($size > $this->maxMessageSize) {
                unset($message);

                continue;
            }

            if ($total + $size > $this->maxTotalSize) {
                unset($message);
                break;
            }

            array_unshift($newMessages, $message);
            $total += $size;
            unset($size, $message);
        }

        $this->messages = $newMessages;
        unset($newMessages, $total);
    }
}
