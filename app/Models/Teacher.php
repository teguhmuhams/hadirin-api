<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nip',
        'birthdate',
        'gender',
        'user_id',
    ];

    /**
     * Define relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Define relationship.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
