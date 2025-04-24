<?php

use Lenorix\LaravelAiExtra\Traits\ChatLimits;
use MalteKuhr\LaravelGPT\Models\ChatMessage;
use MalteKuhr\LaravelGPT\Enums\ChatRole;

it('limits messages by count', function () {
    $chat = new class() {
        use ChatLimits {
            ensureMessagesLimit as public;
        }
        public array $messages = [];
        public function __construct()
        {
            $this->maxMessages = 3;
            for ($i = 1; $i <= 5; $i++) {
                $this->messages[] = new ChatMessage(ChatRole::USER, content: 'msg'.$i);
            }
        }
    };

    expect(count($chat->messages))->toBe(5);
    $chat->ensureMessagesLimit();
    expect(count($chat->messages))->toBe(3);
});

it('limits messages by size', function () {
    $chat = new class {
        use ChatLimits { ensureMessagesLimit as public; }
        public array $messages = [];
        public function __construct()
        {
            $keep1 = new ChatMessage(ChatRole::USER, str_repeat('x', 3));
            $keep2 = new ChatMessage(ChatRole::USER, str_repeat('z', 4));
            $this->maxMessageSize = max(mb_strlen(json_encode($keep1)), mb_strlen(json_encode($keep2))) + 1;
            $this->messages = [
                $keep1,
                new ChatMessage(ChatRole::USER, str_repeat('y', 6)),
                $keep2,
            ];
        }
    };

    expect(count($chat->messages))->toBe(3);
    $chat->ensureMessagesLimit();
    expect(count($chat->messages))->toBe(2);
});

it('limits messages by total size', function () {
    $chat = new class {
        use ChatLimits {
            ensureMessagesLimit as public;
        }
        public array $messages = [];
        public function __construct()
        {
            $keep = new ChatMessage(ChatRole::USER, content: 'bbbbbb');
            $this->maxTotalSize = mb_strlen(json_encode($keep)) + 1;
            $this->messages = [
                new ChatMessage(ChatRole::USER, content: 'aaaaa'),
                $keep,
            ];
        }
    };

    expect($chat->messages[1]->content)->toBe('bbbbbb');
    $chat->ensureMessagesLimit();
    expect(count($chat->messages))->toBe(1);
    expect($chat->messages[0]->content)->toBe('bbbbbb');
});
