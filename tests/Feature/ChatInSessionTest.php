<?php

it('can save and load', function () {
    $oldAiChat = new class() {
        use MalteKuhr\LaravelGPT\Concerns\HasChat;
        use Lenorix\LaravelAiExtra\Traits\ChatInSession {
            getSessionKey as getSessionKeyFromTrait;
        }
        public function getSessionKey(): string
        {
            return 'laravel-ai-chat-test';
        }
    };

    $oldAiChat->addMessage('Hello');

    expect($oldAiChat->latestMessage()->content)->toBe('Hello');
    $oldAiChat->saveChat();
    unset($oldAiChat);

    $newAiChat = new class() {
        use MalteKuhr\LaravelGPT\Concerns\HasChat;
        use Lenorix\LaravelAiExtra\Traits\ChatInSession {
            getSessionKey as getSessionKeyFromTrait;
        }
        public function getSessionKey(): string
        {
            return 'laravel-ai-chat-test';
        }
    };
    $newAiChat->loadChat();
    expect($newAiChat->latestMessage()->content)->toBe('Hello');
});
