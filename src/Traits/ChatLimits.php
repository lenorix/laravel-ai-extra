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
        $totalSize = 0;
        $newMessages = [];
        while (count($newMessages) < $this->maxMessages && ! empty($this->messages)) {
            $message = array_pop($this->messages);

            if (is_string($message->content) && strlen($message->content) > $this->maxMessageSize) {
                unset($message);

                continue;
            }

            $encoded = json_encode($message);
            if ($encoded === false) {
                $newMessages[] = $message;
                unset($encoded);

                continue;
            }

            $size = strlen($encoded);
            unset($encoded);
            if ($size > $this->maxMessageSize) {
                unset($size, $message);

                continue;
            }

            if ($totalSize + $size > $this->maxTotalSize) {
                unset($size, $message);
                break;
            }

            $newMessages[] = $message;
            $totalSize += $size;
            unset($size, $message);
        }

        $this->messages = array_reverse($newMessages);
        unset($newMessages, $totalSize);
    }
}
