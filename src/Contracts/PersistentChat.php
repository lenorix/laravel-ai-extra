<?php

namespace Lenorix\LaravelAiExtra\Contracts;

interface PersistentChat
{
    public function loadChat(): void;

    public function saveChat(): void;
}
