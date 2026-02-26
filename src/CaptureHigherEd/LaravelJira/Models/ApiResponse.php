<?php

namespace CaptureHigherEd\LaravelJira\Models;

interface ApiResponse
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
