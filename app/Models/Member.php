<?php

namespace App\Models;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'age',
        'email',
        'mobile',
        'address'
    ];
}
