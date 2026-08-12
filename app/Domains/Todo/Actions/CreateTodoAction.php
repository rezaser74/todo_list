<?php

declare(strict_types=1);

namespace App\Domains\Todo\Actions;

use App\Domains\Todo\DTOs\StoreTodoDTO;
use App\Models\Todo;

class CreateTodoAction
{
    /**
     * Execute the action to create a new todo.
     */
    public function execute(StoreTodoDTO $dto): Todo
    {
        return Todo::create([
            'title' => $dto->title,
            'is_completed' => $dto->isCompleted ?? false,
        ]);
    }
}
