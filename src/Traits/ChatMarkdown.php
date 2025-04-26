<?php

namespace Lenorix\LaravelAiExtra\Traits;

use Illuminate\Support\Facades\Cache;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use MalteKuhr\LaravelGPT\Models\ChatMessage;

trait ChatMarkdown
{
    use ChatExtra;

    /**
     * Get only user and assistant messages,
     *  rendering markdown to HTML.
     *
     * See: https://commonmark.thephpleague.com/2.6/security/
     *
     * @return array<ChatMessage>
     */
    public function chatMessagesHtml(): array
    {
        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $converter = new MarkdownConverter($environment);

        return array_map(function (ChatMessage $message) use ($converter) {
            if ($message->content === null) {
                return $message;
            }

            $content = Cache::remember(
                key: 'markdown-to-html.'.sha1($message->content),
                ttl: 60 * 60, // TODO: make this configurable
                callback: function () use ($converter, $message) {
                    return $converter
                        ->convert($message->content)
                        ->getContent();
                }
            );

            return new ChatMessage($message->role, content: $content);
        }, $this->chatMessages());
    }
}
