<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'image', 'url', 'quota', 'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function courseContentMinis()
    {
        return $this->hasMany(CourseContentMini::class, 'course_id');
    }

    public function members()
    {
        return $this->hasMany(CourseMember::class, 'course_id');
    }

    public function completionTrackings()
    {
        return $this->hasMany(CourseCompletionTracking::class, 'course_id');
    }
}
