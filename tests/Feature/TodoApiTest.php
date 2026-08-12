<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test GET /api/todos returns a list of todos.
     */
    public function test_can_list_all_todos(): void
    {
        Todo::factory()->count(3)->create();

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'is_completed', 'created_at', 'updated_at'],
                ],
            ]);
    }

    /**
     * Test POST /api/todos creates a todo successfully.
     */
    public function test_can_create_a_todo(): void
    {
        $payload = [
            'title' => 'Buy groceries',
        ];

        $response = $this->postJson('/api/todos', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'Buy groceries',
                    'is_completed' => false,
                ],
            ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Buy groceries',
            'is_completed' => false,
        ]);
    }

    /**
     * Test POST /api/todos validation fails when title is missing.
     */
    public function test_create_todo_validation_fails_without_title(): void
    {
        $response = $this->postJson('/api/todos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test GET /api/todos/{id} displays a specific todo.
     */
    public function test_can_show_a_todo(): void
    {
        $todo = Todo::factory()->create([
            'title' => 'Read a book',
            'is_completed' => false,
        ]);

        $response = $this->getJson("/api/todos/{$todo->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $todo->id,
                    'title' => 'Read a book',
                    'is_completed' => false,
                ],
            ]);
    }

    /**
     * Test GET /api/todos/{id} returns 404 when todo does not exist.
     */
    public function test_show_returns_404_for_non_existent_todo(): void
    {
        $response = $this->getJson('/api/todos/99999');

        $response->assertStatus(404);
    }

    /**
     * Test PUT /api/todos/{id} updates a todo successfully.
     */
    public function test_can_update_a_todo(): void
    {
        $todo = Todo::factory()->create([
            'title' => 'Old Title',
            'is_completed' => false,
        ]);

        $payload = [
            'title' => 'Updated Title',
            'is_completed' => true,
        ];

        $response = $this->putJson("/api/todos/{$todo->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $todo->id,
                    'title' => 'Updated Title',
                    'is_completed' => true,
                ],
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Title',
            'is_completed' => true,
        ]);
    }

    /**
     * Test PUT /api/todos/{id} validation fails with invalid data.
     */
    public function test_update_todo_validation_fails_with_invalid_is_completed(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->putJson("/api/todos/{$todo->id}", [
            'is_completed' => 'not-a-boolean',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_completed']);
    }

    /**
     * Test PUT /api/todos/{id} returns 404 for non-existent todo.
     */
    public function test_update_returns_404_for_non_existent_todo(): void
    {
        $response = $this->putJson('/api/todos/99999', [
            'is_completed' => true,
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test DELETE /api/todos/{id} deletes a todo successfully.
     */
    public function test_can_delete_a_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Deleted successfully']);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    /**
     * Test DELETE /api/todos/{id} returns 404 for non-existent todo.
     */
    public function test_delete_returns_404_for_non_existent_todo(): void
    {
        $response = $this->deleteJson('/api/todos/99999');

        $response->assertStatus(404);
    }
}
