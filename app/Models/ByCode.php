<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ByCode extends Model
{
    use HasFactory;
       protected $table = 'bycodes';

    /**
     * @var array
     */
    protected $guarded = ['id'];

}
