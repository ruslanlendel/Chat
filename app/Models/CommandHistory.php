<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandHistory extends Model
{
    use HasFactory;

    protected $table = 'command_history';

    protected $fillable = ['command', 'result'];
}
