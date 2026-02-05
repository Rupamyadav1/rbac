<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
    ];

  
}
?>