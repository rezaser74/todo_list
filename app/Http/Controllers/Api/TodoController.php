<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Domains\Todo\DTOs\StoreTodoDTO;
use App\Domains\Todo\DTOs\UpdateTodoDTO;
use App\Domains\Todo\Actions\ListTodosAction;
use App\Domains\Todo\Actions\CreateTodoAction;
use App\Domains\Todo\Actions\UpdateTodoAction;
use App\Domains\Todo\Actions\DeleteTodoAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TodoController extends Controller
{
    /**
     * Display a listing of the todos.
     */
    public function index(ListTodosAction $action): AnonymousResourceCollection
    {
        return TodoResource::collection($action->execute());
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreTodoRequest $request, CreateTodoAction $action): JsonResponse
    {
        $dto = StoreTodoDTO::fromRequest($request);
        $todo = $action->execute($dto);

        return (new TodoResource($todo))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified todo.
     */
    public function show(Todo $todo): TodoResource
    {
        return new TodoResource($todo);
    }

    /**
     * Update the specified todo in storage.
     */
    public function update(
        UpdateTodoRequest $request,
        Todo $todo,
        UpdateTodoAction $action
    ): TodoResource {
        $dto = UpdateTodoDTO::fromRequest($request);
        $updatedTodo = $action->execute($todo, $dto);

        return new TodoResource($updatedTodo);
    }

    /**
     * Remove the specified todo from storage.
     */
    public function destroy(Todo $todo, DeleteTodoAction $action): JsonResponse
    {
        $action->execute($todo);

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
