<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCompletionTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'status', 'description', 'member_id', 'content_id'
    ];

    public function member()
    {
        return $this->belongsTo(CourseMember::class, 'member_id', 'id');
    }
    
    public function content()
    {
        return $this->belongsTo(CourseContent::class, 'content_id', 'id');
    }
}
