<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'tbl_setting_user_role';

    // Disable timestamps if you are not using Laravel's default timestamps
    public $timestamps = false;

    // Define the fields that can be mass assigned
    protected $fillable = [
        'role',
        'active',
        'created_by',
        'created_on',
        'changed_by',
        'changed_on'
    ];

    // Define the fields that should be cast to a different type
    protected $casts = [
        'created_on' => 'datetime',
        'changed_on' => 'datetime'
    ];

    // Optionally, you can add relationships if needed
    // For example, if a user role belongs to many users
    public function users()
    {
        return $this->hasMany(User::class, 'user_role', 'id');
    }
}
