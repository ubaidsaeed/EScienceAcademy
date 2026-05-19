<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAttemps extends Model
{
    use HasFactory;

    protected $table = 'payment_attempts';

    protected $fillable = [
        'id',
        'subscription_id',
        'attempt_no',
        'sub_attamp_id'
    ];
}
