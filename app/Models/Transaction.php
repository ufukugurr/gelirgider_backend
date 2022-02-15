<?php

namespace App\Models;

use App\Scopes\UserScope;
use App\Events\TransactionCreating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'currency',
        'amount',
        'description'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => TransactionCreating::class
    ];

    /**
     * Get the category that owns the ledger.
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Interact with the created at.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: fn ($value) => date('d.m.Y H:i', strtotime($value)),
            //set: fn ($value) => strtolower($value),
        );
    }

    /**
     * Interact with the amount.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function amount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => number_format($value, 2, '.', ''),
        );
    }
}
