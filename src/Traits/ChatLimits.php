<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Lenorix\Ai\Chat\CoreMessageRole;

/*
 * Methods to control context limits for maltekuhr/laravel-gpt `GPTChat`.
 *
 * This expects the class has a `messages` property.
 *
 * @method static addMessage(CoreMessage|string $message) Required from `HasChat` trait used in `GPTChat` class.
 * @property array<CoreMessage> $messages Required from `HasChat` trait used in `GPTChat` class.
 */
trait ChatLimits
{
    /**
     * The maximum size of a message in bytes.
     */
    public int $maxMessageSize = 25_000;

    /**
     * The maximum size of all messages in bytes.
     */
    public int $maxTotalSize = 110_000;

    public int $maxMessages = 200;

    public function ensureMessagesLimit(): void
    {
        $totalSize = 0;
        $newMessages = [];
        while (count($newMessages) < $this->maxMessages && ! empty($this->messages)) {
            $message = array_pop($this->messages);

            if (is_string($message->content) && strlen($message->content) > $this->maxMessageSize) {
                if ($message->role == CoreMessageRole::TOOL) {
                    do {
                        $next = array_pop($this->messages);
                    } while ($message->role == CoreMessageRole::TOOL || ($next->role == CoreMessageRole::ASSISTANT && $next->toolCalls !== null));
                    // Assistant tools call must be preceded by tools message, if it is removed then
                    // the assistant request also must be removed.
                }

                continue;
            }

            $encoded = json_encode($message);
            if ($encoded === false) {
                $newMessages[] = $message;

                continue;
            }

            $size = strlen($encoded);
            if ($size > $this->maxMessageSize) {
                continue;
            }

            if ($totalSize + $size > $this->maxTotalSize) {
                if ($message->role == CoreMessageRole::TOOL) {
                    do {
                        $next = array_pop($this->messages);
                    } while ($message->role == CoreMessageRole::TOOL || ($next->role == CoreMessageRole::ASSISTANT && $next->toolCalls !== null));
                    // Assistant tools call must be preceded by tools message, if it is removed then
                    // the assistant request also must be removed.
                }

                continue;
            }

            $newMessages[] = $message;
            $totalSize += $size;
        }

        $this->messages = array_reverse($newMessages);
    }
}
