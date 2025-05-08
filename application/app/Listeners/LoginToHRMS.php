<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LoginToHRMS
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if(request()->cookie('hrms_session') == '') {
            $hrms_cookie = session()->getId();
            Cookie::queue('hrms_session', $hrms_cookie);
            

            
            $last_data = array(
                'last_login_date' => date('d-m-Y H:i:s'),
                'last_login_ip' => request()->ip(),
                'is_logged_in' => '1'
            ); 
            
            $id = Auth::user()->id; 

            DB::table('xin_employees')->where('user_id',$id)->update($last_data);
            DB::table('users')->where('id',$id)->update([
                'last_seen' => date('d-m-Y H:i:s'),
                'last_ip_address' => request()->ip(),
            ]);

        }
    }
}
