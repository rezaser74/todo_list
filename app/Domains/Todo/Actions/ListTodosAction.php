<?php

declare(strict_types=1);

namespace App\Domains\Todo\Actions;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class ListTodosAction
{
    /**
     * Execute the action to list all todos.
     *
     * @return Collection<int, Todo>
     */
    public function execute(): Collection
    {
        return Todo::latest()->get();
    }
}
