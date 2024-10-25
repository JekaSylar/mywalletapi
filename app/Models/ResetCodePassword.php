<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResetCodePassword extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'code', 'expires_at', 'token'];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }
}
