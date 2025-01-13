<?php

namespace App\Http\Controllers;

use App\Models\CourseContent;
use Exception;
use Illuminate\Http\Request;

class CourseContentController extends Controller
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
    public function store(Request $request, $courseId)
    {
        try {

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'video_url' => 'nullable|url',
                'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip',
                'release_start' => 'nullable|date',
                'release_end' => 'nullable|date|after_or_equal:release_start',
                'status' => 'required|in:0,1'
            ]);

            if ($request->hasFile('file_attachment')) {
                $validatedData['file_attachment'] = $request->file('file_attachment')->store('course_contents');
            }

            $validatedData['course_id'] = $courseId;

            $courseContent = CourseContent::create($validatedData);

            $courseContent->status = $courseContent->status == 1 ? "publish" : "unpublish";

            $courseContent->course_id = (int) $courseContent->course_id;

            return response()->json([
                'status' => true,
                'message' => 'Course content added successfully',
                'data' => $courseContent,
                'code' => 200
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add course content: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseContent $courseContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseContent $courseContent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseContent $courseContent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseContent $courseContent)
    {
        //
    }
}
