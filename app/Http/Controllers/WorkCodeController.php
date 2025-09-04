<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\CC_Admin;
use App\Models\CC_Queue_Stats;

class WorkCodeController extends Controller
{

    public function home(){
        $cc_id = Session::get('cc_id');
        $workcodes = DB::table('cc_workcodes_new')->where('cc_id',$cc_id)->paginate(10);
        return view('workCode',compact('workcodes'));
    }

    public function insertCallWorkCode(Request $request){

        $validate = Validator::make($request->all(), [
            'roles' => 'required|array|min:1',
        ]);

        if($validate->fails()){
            Toastr::error('Please select the workcode...', 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }

        $unique_id = Session::get('unique_id');
        $caller_id = Session::get('caller_id');
        $agent_id  = Session::get('admin_id');
        $cc_id     = Session::get('cc_id');

        foreach($request->roles as $workcode){
            DB::table('cc_call_workcodes')->insert([
                'unique_id' => $unique_id,
                'caller_id' => $caller_id,
                'workcodes' => $workcode,
                'staff_id'  => $agent_id,
                'staff_updated_date' => now(), // Laravel's helper to get current date/time
                'cc_id'     => $cc_id
            ]);
        }

        $user = CC_Admin::where('admin_id',$agent_id)->where('cc_id',$cc_id)->first();
        $user->is_busy = 0;
        $user->save();

        $queue_status = CC_Queue_Stats::where('unique_id',$unique_id)->where('cc_id',$cc_id)->get();
        foreach($queue_status as $value){
            $find_record =  CC_Queue_Stats::where('id',$value->id)->first();
            $find_record->status = 1;
            $find_record->save();
        }

        if (!empty(Session::get('caller_id'))) {
            session()->forget('caller_id');
        }

        Toastr::success('Call Ended Successfully.', 'success', ["positionClass" => "toast-top-center"]);
        return redirect()->back();


    }

    public function workcode_status_update(Request $request){
        $validate = Validator::make($request->all(), [
            'admin_id'  => 'required',
            'status'    => 'required'
        ]);

        if($validate->fails()){
            $error = array(
                'status'    => false,
                'error'     => 404,
                'message'   => "Wrong input please validate input felid."
            );
            return response()->json($error,200);
        }
        $cc_id = Session::get('cc_id');
        $user = DB::table('cc_workcodes_new')->where('staff_id',$request->admin_id)->where('cc_id',$cc_id)->first();
        if($user){
            if($request->status == "active"){
                $status = 1;
            }else{
                $status = 0;
            }
            DB::update("update cc_workcodes_new set status = $status where staff_id = ? AND wc_title = ? AND cc_id = ?", [$request->admin_id,$user->wc_title,$cc_id]);
            return array(
                'status'    => true,
                'success'   => 200,
                'message'   => "User Update Successfully."
            );
        }
        return array(
            'status'    => false,
            'error'     => 404,
            'message'   => "User Not Found."
        );
    }

    public function addNewCallWorkCode(Request $request){
        $validate = Validator::make($request->all(), [
            'workcode' => 'required',
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $cc_id = Session::get('cc_id');
        $check = DB::table('cc_workcodes_new')->where('wc_title',$request->workcode)->where('cc_id',$cc_id)->first();
        if($check){
            Toastr::error('Workcode Already Exists...', 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $agent_id  = Session::get('admin_id');
        DB::table('cc_workcodes_new')->insert([
            'wc_title'  => $request->workcode,
            'parent_id' => 1,
            'wc_value'  => 1,
            'staff_id'  => $agent_id,
            'status'    => 1,
            'cc_id'     => $cc_id
        ]);

        Toastr::success('Workcode Add Successfully....', 'success', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }

}
