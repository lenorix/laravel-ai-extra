<?php

namespace Lenorix\LaravelAiExtra\Contracts;

use MalteKuhr\LaravelGPT\Models\ChatMessage;

/**
 * Interface for persistent chat functionality.
 *
 * This must be followed by traits like ChatSession and ChatDatabase
 * even PHP can not check it actually.
 */
interface PersistentChat
{
    /**
     * Load chat messages from a persistent storage.
     *
     * @param int|null $maxLatestMessages Maximum number of latest messages to load.
     * @return static
     */
    public function loadChat(?int $maxLatestMessages=null): static;

    /**
     * Add a message to the chat, storing it or queuing it to ve stored when calling `saveChat()`.
     *
     * @param ChatMessage|string $message The message to add.
     * @return static
     */
    public function addMessage(ChatMessage|string $message): static;

    /**
     * Save the chat messages or do nothing if `addMessage()` stored them.
     *
     * @return static
     */
    public function saveChat(): static;
}
