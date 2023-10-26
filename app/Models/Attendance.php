<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'course_id',
    ];


    /**
     * Define relationship.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Define relationship.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'student_attendance')
            ->withPivot('status', 'longitude', 'latitude')
            ->withTimestamps();;
    }
}
