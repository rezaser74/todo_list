<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Services\TodoService;
use App\Http\Resources\TodoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    ) {}

    /**
     * Display a listing of the todos.
     */
    public function index(): AnonymousResourceCollection
    {
        $todos = $this->todoService->getTodos();

        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreTodoRequest $request): JsonResponse
    {
        $todo = $this->todoService->createTodo($request->validated());

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
    public function update(UpdateTodoRequest $request, Todo $todo): TodoResource
    {
        $updatedTodo = $this->todoService->updateTodo($todo, $request->validated());

        return new TodoResource($updatedTodo);
    }

    /**
     * Remove the specified todo from storage.
     */
    public function destroy(Todo $todo): JsonResponse
    {
        $this->todoService->deleteTodo($todo);

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
