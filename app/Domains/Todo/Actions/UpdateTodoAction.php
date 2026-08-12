<?php

declare(strict_types=1);

namespace App\Domains\Todo\Actions;

use App\Domains\Todo\DTOs\UpdateTodoDTO;
use App\Models\Todo;

class UpdateTodoAction
{
    /**
     * Execute the action to update a todo.
     */
    public function execute(Todo $todo, UpdateTodoDTO $dto): Todo
    {
        $data = array_filter([
            'title' => $dto->title,
            'is_completed' => $dto->isCompleted,
        ], fn (mixed $value) => $value !== null);

        $todo->update($data);

        return $todo->refresh();
    }
}
