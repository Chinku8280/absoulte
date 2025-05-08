<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogoutHRMS
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if (isset($_COOKIE['hrms_session'])) {
            unset($_COOKIE['hrms_session']); 
            setcookie('hrms_session', null, -1, '/'); 
        }
        if (isset($_COOKIE['ci_session'])) { 
            unset($_COOKIE['ci_session']); 
            setcookie('ci_session', null, -1, '/'); 
        }    

        $last_data = array(
            'last_logout_date' => date('d-m-Y H:i:s'),
            'last_login_ip' => request()->ip(),
            'is_logged_in' => '0'
        ); 
        
        $id = Auth::user()->id; 

        DB::table('xin_employees')->where('user_id',$id)->update($last_data);
        DB::table('users')->where('id',$id)->update([
            'last_seen' => date('d-m-Y H:i:s'),
            'last_ip_address' => request()->ip(),
        ]);


    }
}
