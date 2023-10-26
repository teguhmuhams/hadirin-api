<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'year',
        'day',
        'status',
        'grade_id',
        'teacher_id',
    ];

    /**
     * Define relationship.
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Define relationship.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Define relationship.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'student_course')->withTimestamps();
    }

    /**
     * Define relationship.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class)
            ->withPivot('longitude', 'latitude')
            ->withTimestamps();
    }
}
