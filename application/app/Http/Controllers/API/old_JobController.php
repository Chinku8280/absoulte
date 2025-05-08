<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Crm;
use App\Models\JobDetail;
use App\Models\JobDetails;
use App\Models\LeadPaymentInfo;
use App\Models\Quotation;
use App\Models\QuotationServiceDetail;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use App\Models\ServiceAddress;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function pendingjob($user_id)
    {
        $pending_job = ScheduleModel::where('employee_id', $user_id)
            ->where('status', 1)
            ->where('job_status', 'pending')
            ->get();

        $response = [
            'success' => true,
            'message' => 'Pending job retrieved successfully',
            'data' => [
                'pending_job' => $pending_job
            ]
        ];
        return response()->json($response);
    }
    public function completedjob($user_id)
    {
        $completed_job = ScheduleModel::where('employee_id', $user_id)
            ->where('status', 1)
            ->where('job_status', 'completed')
            ->get();

        $response = [
            'success' => true,
            'message' => 'Completed job retrieved successfully',
            'data' => [
                'completed_job' => $completed_job
            ]
        ];
        return response()->json($response);
    }

    public function uploadJobDetails(Request $request)
    {
        $user_id = $request->user()->id;

        $validatedData = $request->validate([
            'schedule_id' => 'required|exists:tble_schedule,id',
            'before_photos' => 'nullable|array',
            'before_photos.*' => 'image|mimes:jpeg,png,jpg,gif',
            'before_remark' => 'nullable|string',
            'after_photos' => 'nullable|array',
            'after_photos.*' => 'image|mimes:jpeg,png,jpg,gif',
            'after_remark' => 'nullable|string',
        ]);

        $beforePhotoNames = [];
        $afterPhotoNames = [];

        if ($request->hasFile('before_photos')) {
            foreach ($request->file('before_photos') as $photo) {
                $fileName = $photo->getClientOriginalName();
                $path = $photo->storeAs('job_details/before_photos', $fileName, 'public');
                $beforePhotoNames[] = $fileName;
            }
        }

        if ($request->hasFile('after_photos')) {
            foreach ($request->file('after_photos') as $photo) {
                $fileName = $photo->getClientOriginalName();
                $path = $photo->storeAs('job_details/after_photos', $fileName, 'public');
                $afterPhotoNames[] = $fileName;
            }
        }

        $beforePhotoNamesString = implode(',', $beforePhotoNames);
        $afterPhotoNamesString = implode(',', $afterPhotoNames);

        $jobDetail = JobDetail::create([
            'schedule_id' => $validatedData['schedule_id'],
            'employee_id' => $user_id,
            'before_photos' => $beforePhotoNamesString,
            'before_remark' => $validatedData['before_remark'],
            'after_photos' => $afterPhotoNamesString,
            'after_remark' => $validatedData['after_remark'],
            'user_id' => $user_id, // Assign the extracted user_id to the job detail
        ]);

        $response = [
            'success' => true,
            'message' => 'Job details uploaded successfully',
            'data' => $jobDetail,
        ];
        return response()->json($response);
    }

    public function updateDamageAndRemark(Request $request)
    {
        $user_id = $request->user()->id;

        $validatedData = $request->validate([
            'job_detail_id' => 'required',
            'damage' => 'required|string',
            'remark' => 'nullable|string',
        ]);

        $jobDetail = JobDetail::findOrFail($validatedData['job_detail_id']);
        $jobDetail->update([
            'damage' => $validatedData['damage'] ?? $jobDetail->damage,
            'remark' => $validatedData['remark'] ?? $jobDetail->remark,
        ]);

        return response()->json(['success' => true, 'message' => 'Damage and remark updated successfully', 'data' => $jobDetail]);
    }

    // public function reviewandRatings(Request $request, $jobDetailId)
    // {
    //     $validatedData = $request->validate([
    //         'rating' => 'required|integer|min:1|max:5',
    //         'comment' => 'nullable|string',
    //         'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    //     ]);

    //     $jobDetail = JobDetail::findOrFail($jobDetailId);
    //     $signature = null;

    //     if ($request->hasFile('signature')) {
    //         $signatureFile = $request->file('signature');
    //         $fileName = $signatureFile->getClientOriginalName();
    //         $path = $signatureFile->storeAs('job_details/signature', $fileName, 'public');
    //         $signature = $fileName;
    //     }

    //     $jobDetail->update([
    //         'rating' => $validatedData['rating'] ?? $jobDetail->rating,
    //         'comment' => $validatedData['comment'] ?? $jobDetail->comment,
    //         'signature' => $signature,
    //     ]);

    //     return response()->json(['success' => true, 'message' => 'Thank you for Rating', 'data' => $jobDetail]);
    // }

    public function reviewAndRatings(Request $request)
    {
        $user_id = $request->user()->id; // Extract user_id from the authorization token

        $validatedData = $request->validate([
            'job_detail_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $jobDetail = JobDetail::findOrFail($validatedData['job_detail_id']);
        $signature = null;

        if ($request->hasFile('signature')) {
            $signatureFile = $request->file('signature');
            $fileName = $signatureFile->getClientOriginalName();
            $path = $signatureFile->storeAs('job_details/signature', $fileName, 'public');
            $signature = $fileName;
        }

        $jobDetail->update([
            'rating' => $validatedData['rating'] ?? $jobDetail->rating,
            'comment' => $validatedData['comment'] ?? $jobDetail->comment,
            'signature' => $signature,
        ]);

        return response()->json(['success' => true, 'message' => 'Thank you for Rating', 'data' => $jobDetail]);
    }

    // public function weeklytask($user_id)
    // {
    //     $pending_jobs = ScheduleModel::where('employee_id', $user_id)
    //         ->where('status', 1)
    //         ->where('job_status', 'pending')
    //         ->get();

    //     $organized_jobs = [];

    //     $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    //     foreach ($daysOfWeek as $day) {
    //         $organized_jobs[$day] = [];
    //     }

    //     foreach ($pending_jobs as $job) {
    //         $selectedDays = explode(',', strtolower($job->selected_days));
    //         foreach ($selectedDays as $selectedDay) {
    //             $organized_jobs[$selectedDay][] = $job;
    //         }
    //     }

    //     $response = [
    //         'success' => true,
    //         'message' => 'Pending jobs retrieved successfully',
    //         'data' => $organized_jobs
    //     ];

    //     return response()->json($response);
    // }

    public function weeklyTask(Request $request)
    {
        $user_id = $request->user()->id;

        $pending_jobs = ScheduleModel::select('tble_schedule.*', 'customers.customer_name')
            ->join('customers', 'tble_schedule.customer_id', '=', 'customers.id')
            ->where('tble_schedule.employee_id', $user_id)
            ->where('tble_schedule.status', 1)
            ->where('tble_schedule.job_status', 'pending')
            ->get();

        $organized_jobs = [];

        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($daysOfWeek as $day) {
            $organized_jobs[$day] = [
                'total_task' => 0, // Initialize total_task to 0
                'tasks' => []
            ];
        }

        foreach ($pending_jobs as $job) {
            $selectedDays = explode(',', strtolower($job->selected_days));
            foreach ($selectedDays as $selectedDay) {
                // Initialize total_task to 0 if not already initialized
                if (!isset($organized_jobs[$selectedDay]['total_task'])) {
                    $organized_jobs[$selectedDay]['total_task'] = 0;
                }
                $organized_jobs[$selectedDay]['total_task']++;
                $organized_jobs[$selectedDay]['tasks'][] = $job;
            }
        }

        $response = [
            'success' => true,
            'message' => 'Weekly jobs retrieved successfully',
            'data' => $organized_jobs
        ];

        return response()->json($response);
    }

    public function getJobdetails(Request $request)
    {
        $user = $request->user();
        $employee_id = $user->id;

        $schedules = ScheduleModel::where('employee_id', $employee_id)->get();

        $jobDetails = [];
        foreach ($schedules as $schedule) {
            $salesOrderNo = $schedule->sales_order_no;

            $salesOrder = SalesOrder::where('sales_order_no', $salesOrderNo)->first();
            if ($salesOrder) {
                $quotationId = $salesOrder->quotation_id;

                $quotationServices = QuotationServiceDetail::where('quotation_id', $quotationId)->get();

            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Job details retrieved successfully',
            'data' => $quotationServices
        ]);
    }
}
