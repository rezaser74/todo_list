<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'is_completed',
    ];
    // is completed task getter
    // business logic for updating a todo
    public function markAsComplete():bool{
       return  $this->update(['is_completed' => true]);
    }
}
