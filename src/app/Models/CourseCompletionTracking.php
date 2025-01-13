<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCompletionTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'status', 'description', 'member_id'
    ];

    public function member()
    {
        return $this->belongsTo(CourseMember::class, 'member_id', 'id');
    }
}
