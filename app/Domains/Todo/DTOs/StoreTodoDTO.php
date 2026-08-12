<?php

declare(strict_types=1);

namespace App\Domains\Todo\DTOs;

use App\Http\Requests\StoreTodoRequest;

readonly class StoreTodoDTO
{
    public function __construct(
        public string $title,
        public ?bool $isCompleted = null
    ) {}

    /**
     * Map request to StoreTodoDTO.
     */
    public static function fromRequest(StoreTodoRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            isCompleted: $request->validated('is_completed')
        );
    }
}
