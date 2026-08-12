<?php

declare(strict_types=1);

namespace App\Domains\Todo\Actions;

use App\Models\Todo;

class DeleteTodoAction
{
    /**
     * Execute the action to delete a todo.
     */
    public function execute(Todo $todo): bool
    {
        return (bool) $todo->delete();
    }
}
