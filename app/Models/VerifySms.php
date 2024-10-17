<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifySms extends Model
{
    use HasFactory;

    protected $table = 'verify_sms';
    protected $fillable = [
        "tel",
        "code",
        "code_id"
    ];
}
