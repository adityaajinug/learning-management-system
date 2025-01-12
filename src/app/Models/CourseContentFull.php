<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContentFull extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'video_url', 'parent_id'
    ];

    public function parentContent()
    {
        return $this->belongsTo(CourseContentMini::class, 'parent_id');
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class, 'content_id');
    }
}
