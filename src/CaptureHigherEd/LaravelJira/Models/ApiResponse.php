<?php

namespace CaptureHigherEd\LaravelJira\Models;

interface ApiResponse
{
    public static function make(array $data): self;

    public function toArray(): array;
}
