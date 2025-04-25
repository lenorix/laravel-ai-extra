<?php

namespace Lenorix\LaravelAiExtra\Traits;

use League\CommonMark\CommonMarkConverter;
use MalteKuhr\LaravelGPT\Models\ChatMessage;

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
        $converter = new CommonMarkConverter;

        return array_map(function (ChatMessage $message) use ($converter) {
            $content = $converter->convert($message->content)->getContent();

            return new ChatMessage($message->role, content: $content);
        }, $this->chatMessages());
    }
}
