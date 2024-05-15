<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table  = "files";

    protected $fillable = [
        'name',
        'original_name',
        'status',
        'rows',
        'execution',
    ];
}
