<?php

namespace App\Http\Controllers;

use App\Models\CourseCompletionTracking;
use App\Models\CourseContent;
use App\Models\CourseMember;
use Illuminate\Http\Request;

class CourseCompletionTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show($courseId)
    {
        try {
            $loggedInUser = auth()->user();

            if (!$loggedInUser->isStudent()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Only students can view completion status',
                    'data' => null
                ], 403);
            }

            $courseMember = CourseMember::where('student_id', $loggedInUser->id)
                ->where('course_id', $courseId)
                ->first();

            if (!$courseMember) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not enrolled in this course',
                    'data' => null
                ], 400);
            }

            $completionTrackings = CourseCompletionTracking::where('member_id', $courseMember->id)
                ->whereHas('content', function ($query) use ($courseId) {
                    $query->where('course_id', $courseId); 
                })
                ->with('content')
                ->get();

            if ($completionTrackings->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No content completion found for this course',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Content completion data fetched successfully',
                'data' => $completionTrackings->map(function ($tracking) {
                    return [
                        'content_name' => $tracking->content->name,
                        'description' => $tracking->description,
                        'status' => $tracking->status,
                        'completed_at' => $tracking->updated_at,
                        'id' => $tracking->id
                    ];
                })
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
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
                    'code' => 200,
                    'message' => 'Only students can mark content as completed',
                    'data' => null
                ], 403);
            }
    
            $courseMember = CourseMember::where('student_id', $loggedInUser->id)
                ->where('course_id', $courseId)
                ->first();
    
            if (!$courseMember) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'You are not enrolled in this course',
                    'data' => null
                ], 400);
            }

            $existingCompletion = CourseCompletionTracking::where('member_id', $courseMember->id)
                ->where('content_id', $contentId)
                ->first();
    
            if ($existingCompletion) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'You have already marked this content as completed',
                    'data' => null
                ], 400);
            }
    
            $courseContent = CourseContent::findOrFail($contentId);
    
            $completionTracking = CourseCompletionTracking::create([
                'status' => 1, 
                'description' => $request->input('description', 'Completed'),
                'member_id' => $courseMember->id,
                'content_id' => $contentId
            ]);
    
            $course = $courseMember->course;
    
            return response()->json([
                'status' => true,
                'message' => 'Completion status updated successfully',
                'data' => [
                    'status' => $completionTracking->status,
                    'description' => $completionTracking->description,
                    'content_name' => $courseContent->name,
                    'course_name' => $course->name,
                    'updated_at' => $completionTracking->updated_at,
                    'created_at' => $completionTracking->created_at,
                    'id' => $completionTracking->id
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId, $contentId, $trackingId)
    {
        try {
            $loggedInUser = auth()->user();

            if (!$loggedInUser->isStudent()) {
                return response()->json([
                    'status' => false,
                    'code' => 200,
                    'message' => 'Only students can delete completion status',
                    'data' => null
                ], 403); 
            }

            $courseMember = CourseMember::where('student_id', $loggedInUser->id)
                ->where('course_id', $courseId)
                ->first();

            if (!$courseMember) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'You are not enrolled in this course',
                    'data' => null
                ], 400);
            }

            $completion = CourseCompletionTracking::where('member_id', $courseMember->id)
                ->where('content_id', $contentId)
                ->where('id', $trackingId)
                ->first();

            if (!$completion) {
                return response()->json([
                    'status' => false,
                    'code' => 404,
                    'message' => 'Completion data not found',
                    'data' => null
                ], 404);
            }

            $completion->delete();

            return response()->json([
                'status' => true,
                'message' => 'Completion status deleted successfully',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    
    }
}
