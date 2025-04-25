<?php

namespace Lenorix\LaravelAiExtra\Traits;

use MalteKuhr\LaravelGPT\Enums\ChatRole;
use MalteKuhr\LaravelGPT\Models\ChatMessage;

/**
 * Extra methods for maltekuhr/laravel-gpt `GPTChat`.
 *
 * This expects the class has a `messages` property and an `addMessage` method,
 *  which is the case for `GPTChat` or using the `HasChat` trait.
 */
trait ChatExtra
{
    public function addAssistantMessage(string $message): static
    {
        return $this->addMessage(new ChatMessage(ChatRole::ASSISTANT, content: $message));
    }

    public function addUserMessage(string $message): static
    {
        return $this->addMessage(new ChatMessage(ChatRole::USER, content: $message));
    }

    public function popLatestMessage(): ?ChatMessage
    {
        return array_pop($this->messages);
    }

    /**
     * Get only user and assistant messages with content.
     *
     * @return array<ChatMessage>
     */
    public function chatMessages(): array
    {
        return array_filter($this->messages, function (ChatMessage $message) {
            return $message->content && ($message->role === ChatRole::USER || $message->role === ChatRole::ASSISTANT);
        });
    }

    public function clearMessages(): static
    {
        $this->messages = [];

        return $this;
    }
}
