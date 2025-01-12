<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContentMini extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'course_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function courseContentFulls()
    {
        return $this->hasMany(CourseContentFull::class, 'parent_id');
    }
}
