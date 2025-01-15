<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\CourseContent;
use App\Models\CourseMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $courseId, $contentId)
    {
        try {

            $loggedInUser = auth()->user();
            if (!$loggedInUser->isStudent()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only students can comment on courses',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $validated = $request->validate([
                'comment' => 'required|string|max:1000',
            ]);

            $courseMember = CourseMember::where('student_id', $loggedInUser->id)
                ->where('course_id', $courseId) 
                ->first();

            if (!$courseMember) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not enrolled in this course',
                    'code' => 400,
                    'data' => null
                ], 400);
            }

            
            $courseContent = DB::table('course_contents')
                ->where('course_id', $courseId) 
                ->where('id', $contentId)
                ->first();

            if (!$courseContent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Content not found in this course',
                    'code' => 400,
                    'data' => null
                ], 400);
            }

            
            $comment = CourseComment::create([
                'comment' => $validated['comment'],
                'visible' => false, 
                'content_id' => $contentId,
                'member_id' => $courseMember->id
            ]);

            $comment->load('courseContent');

            return response()->json([
                'status' => true,
                'message' => 'Comment successfully added',
                'data' => [
                    'comment' => $comment->comment,
                    'visible' => $comment->visible,
                    'content' => [
                        'id' => $comment->courseContent->id,
                        'name' => $comment->courseContent->name,
                    ],
                    'member_id' => $comment->member_id,
                    'updated_at' => $comment->updated_at,
                    'created_at' => $comment->created_at,
                    'id' => $comment->id
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    
    }

    public function updateCommentVisibility(Request $request, $courseId, $contentId, $commentId)
    {
        try {
    
            $loggedInUser = auth()->user();
            if (!$loggedInUser->isTeacher()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only teachers can update comment visibility',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found',
                    'code' => 400,
                    'data' => null
                ], 400);
            }

            if ($course->teacher_id !== $loggedInUser->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: You are not the teacher for this course',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $courseContent = CourseContent::where('course_id', $courseId)
                ->where('id', $contentId)
                ->first();

            if (!$courseContent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Content not found for this course',
                    'code' => 400,
                    'data' => null
                ], 400);
            }
            
            $courseComment = CourseComment::where('id', $commentId)
                ->where('content_id', $contentId)
                ->first();

            if (!$courseComment) {
                return response()->json([
                    'status' => false,
                    'message' => 'Comment not found',
                    'code' => 400,
                    'data' => null
                ], 400);
            }

            $courseComment->update([
                'visible' => true
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Comment visibility updated successfully',
                'data' => [
                    'id' => $courseComment->id,
                    'comment' => $courseComment->comment,
                    'visible' => $courseComment->visible,
                    'content' => [
                        'id' => $courseContent->id,
                        'name' => $courseContent->name,
                    ],
                    'updated_at' => $courseComment->updated_at,
                    'created_at' => $courseComment->created_at
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    }
}
