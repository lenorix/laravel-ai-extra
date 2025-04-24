<?php

use Lenorix\LaravelAiExtra\Traits\ChatLimits;
use MalteKuhr\LaravelGPT\Enums\ChatRole;
use MalteKuhr\LaravelGPT\Models\ChatMessage;

it('limits messages by count', function () {
    $chat = new class
    {
        use ChatLimits { ensureMessagesLimit as public; }

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
    $chat = new class
    {
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
    $chat = new class
    {
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

it('push memory usage to the limit', function () {
    $chat = new class
    {
        use ChatLimits { ensureMessagesLimit as public; }

        public array $messages = [];

        public function __construct()
        {
            for ($i = 1; $i <= $this->maxMessages * 2; $i++) {
                $this->messages[] = new ChatMessage(ChatRole::USER, content: str_repeat('x', $this->maxMessageSize * 30));
            }
            for ($i = 1; $i <= $this->maxMessages + 100; $i++) {
                $this->messages[] = new ChatMessage(ChatRole::USER, content: str_repeat('x', $this->maxMessageSize - 300));
            }
            $this->messages[] = new ChatMessage(ChatRole::USER, content: str_repeat('x', $this->maxMessageSize * 30));
        }
    };

    $memoryBefore = memory_get_usage();
    $chat->ensureMessagesLimit();
    $memoryAfter = memory_get_usage();

    expect($memoryAfter)->toBeLessThan($memoryBefore);
    expect(count($chat->messages))->toBeGreaterThan(0);

    $memoryBefore = memory_get_usage();
    for ($i = 1; $i <= 100; $i++) {
        $chat->ensureMessagesLimit();
    }
    $memoryAfter = memory_get_usage();

    expect($memoryAfter)->toBeLessThanOrEqual($memoryBefore);
});
