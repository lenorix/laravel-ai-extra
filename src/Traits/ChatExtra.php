<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Lenorix\Ai\Chat\CoreMessage;
use Lenorix\Ai\Chat\CoreMessageRole;
use MalteKuhr\LaravelGPT\Enums\ChatRole;
use MalteKuhr\LaravelGPT\Models\ChatMessage;

/**
 * Extra methods for maltekuhr/laravel-gpt `GPTChat`.
 *
 * This expects the class has a `messages` property and an `addMessage` method,
 *  which is the case for `GPTChat` or using the `HasChat` trait.
 *
 * @method static addMessage(CoreMessage|string $message) Required from `HasChat` trait used in `GPTChat` class.
 *
 * @property array<CoreMessage> $messages Required from `HasChat` trait used in `GPTChat` class.
 */
trait ChatExtra
{
    public function addAssistantMessage(string $message): static
    {
        return $this->addMessage(new CoreMessage(CoreMessageRole::ASSISTANT, content: $message));
    }

    public function addUserMessage(string $message): static
    {
        return $this->addMessage(new CoreMessage(CoreMessageRole::USER, content: $message));
    }

    public function popLatestMessage(): ?CoreMessage
    {
        return array_pop($this->messages);
    }

    /**
     * Get only user and assistant messages with content.
     *
     * @return array<CoreMessage>
     */
    public function chatMessages(): array
    {
        return array_filter($this->messages, function (CoreMessage $message) {
            return $message->content && ($message->role === CoreMessageRole::USER || $message->role === CoreMessageRole::ASSISTANT);
        });
    }

    public function clearMessages(): static
    {
        $this->messages = []; // @phpstan-ignore-line It's a property from the `HasChat` trait.

        return $this;
    }
}
