<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;
    protected $table = 'course_contents';
    protected $fillable = [
        'name',
        'description',
        'video_url',
        'file_attachment',
        'release_start',
        'release_end',
        'status',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class, 'content_id');
    }

    public function completionTrackings()
    {
        return $this->hasMany(CourseCompletionTracking::class, 'content_id');
    }
  
}
