<?php

namespace Lenorix\LaravelAiExtra\Traits;

use MalteKuhr\LaravelGPT\Enums\ChatRole;
use MalteKuhr\LaravelGPT\Models\ChatMessage;

/**
 * Extra methods for maltekuhr/laravel-gpt `GPTChat`.
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

    /**
     * Get only user and assistant messages.
     *
     * @return array<ChatMessage>
     */
    public function chatMessages(): array
    {
        return array_filter($this->messages, function (ChatMessage $message) {
            return $message->role === ChatRole::USER || $message->role === ChatRole::ASSISTANT;
        });
    }

    public function clearMessages(): static
    {
        $this->messages = [];

        return $this;
    }
}
