<?php

namespace Lenorix\LaravelAiExtra\Traits;

use MalteKuhr\LaravelGPT\Enums\ChatRole;
use MalteKuhr\LaravelGPT\Models\ChatMessage;
use League\CommonMark\CommonMarkConverter;

trait ChatMarkdown
{
    use ChatExtra;

    /**
     * Get only user and assistant messages,
     *  rendering markdown to HTML.
     *
     * @return array<ChatMessage>
     */
    public function chatMessagesHtml(): array
    {
        $converter = new CommonMarkConverter();
        return array_map(function (ChatMessage $message) use ($converter) {
            $content = $converter->convert($message->content)->getContent();
            return new ChatMessage($message->role, content: $content);
        }, $this->chatMessages());
    }
}
