<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_TEACHER = 1;
    const ROLE_STUDENT = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'username', 'roles'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function isTeacher(): bool
    {
        return $this->roles === self::ROLE_TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->roles === self::ROLE_STUDENT;
    }


    public function coursesTaught()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function courseMemberships()
    {
        return $this->hasMany(CourseMember::class, 'student_id');
    }

    public function completionTrackings()
    {
        return $this->hasMany(CourseCompletionTracking::class, 'student_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
