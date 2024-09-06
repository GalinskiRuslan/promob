<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'table_statistics_for_executors';

    protected $fillable = [
        'user_id',
        'view_count',
        'click_contacts',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
