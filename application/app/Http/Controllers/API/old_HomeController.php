<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ScheduleModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getMonthlyReview($user_id)
    {
        $pendingCount = ScheduleModel::where('employee_id', $user_id)->where('job_status', 0)->count();
        $progressCount = ScheduleModel::where('employee_id', $user_id)->where('job_status', 1)->count();
        $completedCount = ScheduleModel::where('employee_id', $user_id)->where('job_status', 2)->count();
        $response = [
            'success' => true,
            'message' => 'Monthly Review retrieved successfully',
            'data' => [
                'pending_count' => $pendingCount,
                'progress_count' => $progressCount,
                'completed_count' => $completedCount,
            ]
        ];

        return response()->json($response);
    }
}
