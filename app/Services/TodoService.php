<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class TodoService
{
    /**
     * Retrieve all todos.
     */
    public function getTodos(): Collection
    {
        return Todo::latest()->get();
    }

    /**
     * Retrieve a todo by ID.
     */
    public function getTodoById(int $id): Todo
    {
        return Todo::findOrFail($id);
    }

    /**
     * Find todos by title/name.
     */
    public function findByName(string $name): Collection
    {
        return Todo::where('title', 'like', "%{$name}%")->get();
    }

    /**
     * Create a new todo item.
     */
    public function createTodo(array $data): Todo
    {
        return Todo::create([
            'title' => $data['title'],
            'is_completed' => $data['is_completed'] ?? false,
        ]);
    }

    /**
     * Update an existing todo item.
     */
    public function updateTodo(Todo $todo, array $data): Todo
    {
        $todo->update($data);

        return $todo->refresh();
    }

    /**
     * Mark a todo item as complete.
     */
    public function completeTodo(Todo $todo): Todo
    {
        $todo->markAsComplete();

        return $todo->refresh();
    }

    /**
     * Delete a todo item.
     */
    public function deleteTodo(Todo $todo): bool
    {
        return (bool) $todo->delete();
    }
}
