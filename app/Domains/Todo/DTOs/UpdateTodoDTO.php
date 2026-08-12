<?php

declare(strict_types=1);

namespace App\Domains\Todo\DTOs;

use App\Http\Requests\UpdateTodoRequest;

readonly class UpdateTodoDTO
{
    public function __construct(
        public ?string $title = null,
        public ?bool $isCompleted = null
    ) {}

    /**
     * Map request to UpdateTodoDTO.
     */
    public static function fromRequest(UpdateTodoRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            isCompleted: $request->validated('is_completed')
        );
    }
}
