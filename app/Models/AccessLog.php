<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'route',
        'ip',
    ];

    public $timestamps = false;
}
