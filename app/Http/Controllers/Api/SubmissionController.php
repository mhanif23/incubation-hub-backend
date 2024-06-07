<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:Akademisi,Peneliti,Sektor Swasta,Pemerintahan,Masyarakat Umum',
            'description' => 'required|string',
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Validation Error', [], $validator->errors()->toArray());
        }

        $documentPath = $request->file('document')->store('submissions');

        $submission = Submission::create([
            'user_id' => $request->user()->id,
            'role' => $request->role,
            'description' => $request->description,
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        return $this->api_response_success('Submission created successfully', ['submission' => $submission], [], 201);
    }

    public function index(Request $request)
    {
        $submissions = Submission::where('user_id', $request->user()->id)->get();

        return $this->api_response_success('Submissions retrieved successfully', ['submissions' => $submissions]);
    }

    public function show($id)
    {
        $submissions = Submission::all();

        return $this->api_response_success('Submissions retrieved successfully', ['submissions' => $submissions]);
    }
    
    public function destroy($id)
    {
        $submission = Submission::find($id);

        if (!$submission || $submission->user_id != auth()->id()) {
            return $this->api_response_error('Submission not found or access denied', [], [], 404);
        }

        Storage::delete($submission->document_path);
        $submission->delete();

        return $this->api_response_success('Submission deleted successfully');
    }

    public function approve($id)
    {
        $submission = Submission::find($id);

        if (!$submission) {
            return $this->api_response_error('Submission not found', [], [], 404);
        }

        $submission->status = 'approved';
        $submission->save();

        return $this->api_response_success('Submission approved successfully', ['submission' => $submission]);
    }

    public function reject($id)
    {
        $submission = Submission::find($id);

        if (!$submission) {
            return $this->api_response_error('Submission not found', [], [], 404);
        }

        $submission->status = 'rejected';
        $submission->save();

        return $this->api_response_success('Submission rejected successfully', ['submission' => $submission]);
    }
}
