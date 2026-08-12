<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TodoService $todoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->todoService = new TodoService();
    }

    public function test_get_todos_returns_all_todos(): void
    {
        Todo::factory()->count(3)->create();

        $todos = $this->todoService->getTodos();

        $this->assertCount(3, $todos);
    }

    public function test_get_todo_by_id_returns_correct_todo(): void
    {
        $todo = Todo::factory()->create(['title' => 'Specific Todo']);

        $foundTodo = $this->todoService->getTodoById($todo->id);

        $this->assertEquals('Specific Todo', $foundTodo->title);
    }

    public function test_create_todo_creates_record_in_database(): void
    {
        $todo = $this->todoService->createTodo([
            'title' => 'New Service Todo',
            'is_completed' => false,
        ]);

        $this->assertInstanceOf(Todo::class, $todo);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'New Service Todo',
            'is_completed' => false,
        ]);
    }

    public function test_update_todo_updates_database_record(): void
    {
        $todo = Todo::factory()->create([
            'title' => 'Initial Title',
            'is_completed' => false,
        ]);

        $updatedTodo = $this->todoService->updateTodo($todo, [
            'title' => 'Changed Title',
            'is_completed' => true,
        ]);

        $this->assertEquals('Changed Title', $updatedTodo->title);
        $this->assertTrue($updatedTodo->is_completed);
    }

    public function test_complete_todo_marks_todo_as_completed(): void
    {
        $todo = Todo::factory()->create(['is_completed' => false]);

        $completedTodo = $this->todoService->completeTodo($todo);

        $this->assertTrue($completedTodo->is_completed);
    }

    public function test_delete_todo_removes_todo_from_database(): void
    {
        $todo = Todo::factory()->create();

        $result = $this->todoService->deleteTodo($todo);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
