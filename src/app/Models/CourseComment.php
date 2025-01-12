<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment', 'visible', 'content_id', 'member_id'
    ];

    public function courseContentFull()
    {
        return $this->belongsTo(CourseContentFull::class, 'content_id');
    }

    public function member()
    {
        return $this->belongsTo(CourseMember::class, 'member_id');
    }
}
