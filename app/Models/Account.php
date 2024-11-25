<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;


    protected $fillable = ['name', 'balance', 'currency', 'user_id'];



    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class);
    }
}
