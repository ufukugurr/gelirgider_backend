<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TransactionCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The transaction instance.
     *
     * @var \App\Models\Transaction
     */
    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transaction->user_id = Auth::id();
    }
}
