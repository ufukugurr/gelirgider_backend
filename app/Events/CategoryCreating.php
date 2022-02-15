<?php

namespace App\Events;

use App\Models\Category;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CategoryCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The category instance.
     *
     * @var \App\Models\Category
     */
    public $category;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->category->user_id = Auth::id();
    }
}
