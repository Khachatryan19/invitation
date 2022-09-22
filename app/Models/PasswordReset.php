<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed|\Ramsey\Uuid\UuidInterface $token
 * @property mixed                            $email
 */
class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';

    protected $fillable = [
        'token',
        'email'
    ];

    public $timestamps = false;

    public function user() : BelongsTo
    {
        return $this->belongsTo('users', 'email', 'email');
    }

}
