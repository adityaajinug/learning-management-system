<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Exception;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::with('teacher')->get();

            return response()->json([
                'status' => true,
                'message' => 'Courses fetched successfully',
                'code' => 200,
                'data' => $courses
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'image' => 'nullable|string',
                'url' => 'nullable|string',
                'quota' => 'required|integer',
                'teacher_id' => 'required|exists:users,id',
            ]);

            $user = auth()->user();
            if (!$user || !$user->isTeacher()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Only teachers can create courses.',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $course = Course::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Course created successfully',
                'code' => 200,
                'data' => $course
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $course = Course::with(['teacher', 'courseContentMinis', 'members', 'completionTrackings'])->find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found',
                    'code' => 404,
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Course fetched successfully',
                'code' => 200,
                'data' => $course
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, Course $course)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric',
                'image' => 'nullable|string',
                'url' => 'nullable|string',
                'quota' => 'sometimes|required|integer',
                'teacher_id' => 'sometimes|required|exists:users,id',
            ]);

            $user = auth()->user();
            if (!$user || (!$user->isTeacher() && $user->id !== $course->teacher_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Only the teacher or course owner can update this course.',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $course->update($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Course updated successfully',
                'code' => 200,
                'data' => $course
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    }

    public function destroy(Course $course)
    {
        try {
            $user = auth()->user();
            if (!$user || (!$user->isTeacher() && $user->id !== $course->teacher_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Only the teacher or course owner can delete this course.',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $course->delete();

            return response()->json([
                'status' => true,
                'message' => 'Course deleted successfully',
                'code' => 200,
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'code' => 500,
                'data' => null
            ], 500);
        }
    }
}