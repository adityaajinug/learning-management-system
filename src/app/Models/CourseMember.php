<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class, 'member_id');
    }

    public function completionTracking()
    {
        return $this->hasMany(CourseCompletionTracking::class, 'member_id');
    }
}
