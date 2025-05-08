<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // get_all_notification

    public function get_all_notification()
    {
        $user_id = Auth::user()->id;

        $notifications = auth()->user()->notifications()->get();

        auth()->user()->unreadNotifications->markAsRead();

        $result = [];

        foreach($notifications as $item)
        {
            if($item->type == 'App\Notifications\CleanerNotification')
            {
                $result[] = [
                    'id' => $item->id,
                    'user_id' => $item->data['user_id'],
                    'message' => $item->data['message'],
                    'created_at' => date('d-m-Y h:i:s A', strtotime($item->created_at))
                ];
            }
        }

        if(!empty($result))
        {
            return response()->json([
                'status' => true,
                'message' => 'Notification list',
                'data' => $result
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found',
                'data' => $result
            ]);
        }
    }
}
