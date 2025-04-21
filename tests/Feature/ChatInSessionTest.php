<?php

it('save and load', function () {
    $oldAiChat = new class
    {
        use Lenorix\LaravelAiExtra\Traits\ChatSession {
            getSessionKey as getSessionKeyFromTrait;
        }
        use MalteKuhr\LaravelGPT\Concerns\HasChat;

        public function getSessionKey(): string
        {
            return 'laravel-ai-chat-test';
        }
    };

    $oldAiChat->addMessage('Hello');

    expect($oldAiChat->latestMessage()->content)->toBe('Hello');
    $oldAiChat->saveChat();
    unset($oldAiChat);

    $newAiChat = new class
    {
        use Lenorix\LaravelAiExtra\Traits\ChatSession {
            getSessionKey as getSessionKeyFromTrait;
        }
        use MalteKuhr\LaravelGPT\Concerns\HasChat;

        public function getSessionKey(): string
        {
            return 'laravel-ai-chat-test';
        }
    };
    $newAiChat->loadChat();
    expect($newAiChat->latestMessage()->content)->toBe('Hello');
});
