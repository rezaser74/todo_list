<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Todo;
use App\Domains\Todo\DTOs\StoreTodoDTO;
use App\Domains\Todo\DTOs\UpdateTodoDTO;
use App\Domains\Todo\Actions\ListTodosAction;
use App\Domains\Todo\Actions\CreateTodoAction;
use App\Domains\Todo\Actions\UpdateTodoAction;
use App\Domains\Todo\Actions\DeleteTodoAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_todos_action_returns_all_todos(): void
    {
        Todo::factory()->count(3)->create();

        $action = new ListTodosAction();
        $todos = $action->execute();

        $this->assertCount(3, $todos);
    }

    public function test_create_todo_action_creates_record_in_database(): void
    {
        $dto = new StoreTodoDTO(
            title: 'New Service Todo',
            isCompleted: false
        );

        $action = new CreateTodoAction();
        $todo = $action->execute($dto);

        $this->assertInstanceOf(Todo::class, $todo);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'New Service Todo',
            'is_completed' => false,
        ]);
    }

    public function test_update_todo_action_updates_database_record(): void
    {
        $todo = Todo::factory()->create([
            'title' => 'Initial Title',
            'is_completed' => false,
        ]);

        $dto = new UpdateTodoDTO(
            title: 'Changed Title',
            isCompleted: true
        );

        $action = new UpdateTodoAction();
        $updatedTodo = $action->execute($todo, $dto);

        $this->assertEquals('Changed Title', $updatedTodo->title);
        $this->assertTrue($updatedTodo->is_completed);
    }

    public function test_delete_todo_action_removes_todo_from_database(): void
    {
        $todo = Todo::factory()->create();

        $action = new DeleteTodoAction();
        $result = $action->execute($todo);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
