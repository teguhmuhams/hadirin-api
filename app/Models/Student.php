<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nisn',
        'user_id',
        'birthdate',
        'gender',
    ];

    /**
     * Define relationship.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_course');
    }
}
