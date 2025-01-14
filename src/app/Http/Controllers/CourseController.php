<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMember;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $loggedInUser = auth()->user();
    
            if (!$loggedInUser->isTeacher()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only teachers can access this data',
                    'code' => 403,
                    'data' => null
                ], 403);
            }
    
            $baseUrl = url('/');
    
            $courses = Course::with('teacher')
                ->where('teacher_id', $loggedInUser->id)
                ->get();
    
            $courses->map(function ($course) use ($baseUrl) {
                $course->url = $baseUrl . '/courses/' . $course->url;
    
                $course->teacher->makeHidden(['roles', 'created_at', 'updated_at']);
    
                return $course;
            });
    
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

        $user = auth()->user();

        if (!$user->isTeacher()) {
            return response()->json([
                'status' => false,
                'message' => 'Only teachers can create courses.',
                'code' => 403,
                'data' => null,
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quota' => 'required|integer',
            'image' => 'required|image|max:2048', 
        ]);

       
        $imagePath = $request->file('image')->store('images/courses', 'public');

  
        $slug = Str::slug($validated['name']);


        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'teacher_id' => $user->id,
            'image' =>  $imagePath,
            'url' => $slug,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Course created successfully.',
            'code' => 200,
            'data' => $course,
        ], 200);
    }

    public function show($id)
    {
        try {
            $course = Course::with(['teacher', 'courseContent', 'members.student'])->find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found',
                    'code' => 404,
                    'data' => null
                ], 404);
            }

            $formattedCourseContent = $course->courseContent->map(function ($content) {
                return [
                    'id' => $content->id,
                    'name' => $content->name,
                    'description' => $content->description,
                    'description' => $content->description,
                    'release_start' => $content->release_start,
                    'release_end' => $content->release_end,
                ];
            });

            $formattedMembers = $course->members->map(function ($member) {
                return [
                    'id' => $member->id,
                    'student' => [
                        'id' => $member->student->id,
                        'username' => $member->student->username,
                        'firstname' => $member->student->firstname,
                        'lastname' => $member->student->lastname,
                        'email' => $member->student->email,
                    ],
                ];
            });
    
            $formattedTeacher = $course->teacher ? [
                'id' => $course->teacher->id,
                'username' => $course->teacher->username,
                'firstname' => $course->teacher->firstname,
                'lastname' => $course->teacher->lastname,
                'email' => $course->teacher->email,
            ] : null;

            return response()->json([
                'status' => true,
                'message' => 'Course fetched successfully',
                'code' => 200,
                'data' => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'price' => $course->price,
                    'quota' => $course->quota,
                    'image' => $course->image,
                    'content' => $formattedCourseContent,
                    'teacher' => $formattedTeacher,
                    'members' => $formattedMembers,
                ]
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

    public function enroll(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId); 
            $studentIds = $request->input('student_ids', []); 

     
            if (empty($studentIds) || !is_array($studentIds)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student IDs are required and must be an array',
                    'data' => null
                ], 400);
            }

            $students = User::whereIn('id', $studentIds)
            ->where('roles', User::ROLE_STUDENT)
            ->get();

            $students = User::whereIn('id', $studentIds)->where('roles', User::ROLE_STUDENT)->get();
            
            if ($students->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid students found',
                    'data' => null
                ], 400);
            }

            $enrolledStudents = [];
            $skippedStudents = [];

            foreach ($students as $student) {
                $alreadyEnrolled = CourseMember::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();
    
                if (!$alreadyEnrolled) {
                    CourseMember::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id
                    ]);
    
                    $enrolledStudents[] = [
                        'id' => $student->id,
                        'firstname' => $student->firstname,
                        'lastname' => $student->lastname,
                        'email' => $student->email,
                        'course_id' => $course->id
                    ];
                } else {
                    $skippedStudents[] = [
                        'id' => $student->id,
                        'firstname' => $student->firstname,
                        'lastname' => $student->lastname,
                        'email' => $student->email,
                        'course_id' => $course->id,
                        'message' => 'Student already enrolled in this course'
                    ];
                }
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Enrollment process completed',
                'data' => [
                    'enrolled' => $enrolledStudents,
                    'skipped' => $skippedStudents
                ],
                'code' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage(),
                'data' => null,
                'code' => 500
            ], 500);
        }
    }

    public function countCoursesByTeacher()
    {
        try {
            $loggedInUser = auth()->user();

            if (!$loggedInUser->isTeacher()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only teachers can access this data',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $courseCount = \Illuminate\Support\Facades\DB::table('courses')->where('teacher_id', $loggedInUser->id)->count();

            return response()->json([
                'status' => true,
                'message' => $courseCount > 0 ? 'Course count fetched successfully' : 'No courses found for the current teacher',
                'code' => 200,
                'data' => [
                    'course_count' => $courseCount
                ]
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

    public function countMembersByTeacher()
    {
        try {
            $loggedInUser = auth()->user();

            if (!$loggedInUser->isTeacher()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only teachers can access this data',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $courses = Course::where('teacher_id', $loggedInUser->id)->get();

            $coursesWithMemberCount = $courses->map(function ($course) {
                return [
                    'course_id' => $course->id,
                    'course_name' => $course->name,
                    'member_count' => $course->members()->count(),
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Course member counts fetched successfully',
                'code' => 200,
                'data' => $coursesWithMemberCount
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

    public function countCommentsByTeacher()
    {
        try {
            $loggedInUser = auth()->user();

            if (!$loggedInUser->isTeacher()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only teachers can access this data',
                    'code' => 403,
                    'data' => null
                ], 403);
            }

            $courses = Course::where('teacher_id', $loggedInUser->id)->get();

            $coursesWithCommentCount = $courses->map(function ($course) {
                $courseContentsWithCommentCount = $course->courseContent->map(function ($content) {
                    $commentCount = $content->comments()->count();

                    return [
                        'content_id' => $content->id,
                        'content_name' => $content->name,
                        'comment_count' => $commentCount, 
                    ];
                });

                return [
                    'course_id' => $course->id,
                    'course_name' => $course->name,
                    'content' => $courseContentsWithCommentCount,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Course comment counts fetched successfully',
                'code' => 200,
                'data' => $coursesWithCommentCount
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