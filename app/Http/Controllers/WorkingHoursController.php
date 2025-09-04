<?php

namespace App\Http\Controllers;

use App\Models\CC_Admin;
use Illuminate\Http\Request;
use App\Models\CC_Working_Hours;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class WorkingHoursController extends Controller
{
    public function show_working_hours(Request $request){
        $hours = CC_Working_Hours::where('cc_id',Session::get('cc_id'))->get();
        return view('workingHours',compact('hours'));
    }

    public function view_working_hours($cc_id){
        $hours = CC_Working_Hours::where('cc_id',$cc_id)->get();
        $agentDropDown  = CC_Admin::where('cc_id',$cc_id)->get();
        return view('updateWorkingHours',compact('hours','agentDropDown'));
    }

    public function update_working_hours(Request $request){
        $week = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        CC_Working_Hours::where('cc_id',Session::get('cc_id'))->delete();
        foreach($week as $key => $day){
            CC_Working_Hours::create([
                'cc_id'             => Session::get('cc_id'),
                'today'             => $day,
                'start_time'        => $request->start_time[$key],
                'end_time'          => $request->end_time[$key],
                'option_off_time'   => $request->agent_id[$key],
            ]);
        }
        Toastr::success('Off Time Working Hours has been Updated successfully...', 'success', ["positionClass" => "toast-top-right"]);
        return redirect('settings/workingHours');

    }

    public function reset_working_hours($cc_id){
        $week = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        CC_Working_Hours::where('cc_id',$cc_id)->delete();
        foreach($week as $key => $day){
            CC_Working_Hours::create([
                'cc_id'             => $cc_id,
                'today'             => $day,
                'start_time'        => "00:00",
                'end_time'          => "23:59",
                'option_off_time'   => "ivr"
            ]);
        }
        Toastr::success('Off Time Working Hours has been Reset successfully...', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

}
