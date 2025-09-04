<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CC_Admin;
use Illuminate\Http\Request;
use App\Models\CC_Notifiables;
use App\Models\CC_Notification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function notification_list(Request $request){
        $cc_id = Session::get('cc_id');
        $cdrs = CC_Notification::where('cc_id',$cc_id)->orderBy('id', 'desc')->paginate(10);
        return view('notification_list',compact('cdrs'));
    }

    public function notification(Request $request){
        $cc_id = Session::get('cc_id');
        $agentDropDown = CC_Admin::where('cc_id',$cc_id)->where('designation','Agents')->get();
        return view('notification_add',compact('agentDropDown'));
    }

    public function notification_add(Request $request){
        $validate = Validator::make($request->all(), [
            'text'          => 'required|string',
            'status'        => 'required|integer',
            'reciever_id'   => 'nullable|integer',
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $cc_id = Session::get('cc_id');
        $staff_id = Session::get('admin_id');
        $text = trim($request->text);
        $status = $request->status;
        $reciever_id = isset($request->reciever_id) ? trim($request->reciever_id) : null;

        $notification = CC_Notification::create([
            'type' => 'alert',
            'text' => $text,
            'notifying_user_id' => $staff_id,
            'status' => $status,
            'cc_id' => $cc_id
        ]);

        if ($notification) {
            $receivers = CC_Admin::where('cc_id',$cc_id)->where('status', 1)
                ->when($reciever_id, function ($query) use ($reciever_id) {
                    return $query->where('admin_id', $reciever_id);
                })
                ->get();

            foreach ($receivers as $receiver) {
                CC_Notifiables::create([
                    'notification_id' => $notification->id,
                    'status' => $status,
                    'reciever_id' => $receiver->admin_id,
                    'cc_id' => $cc_id
                ]);
            }

            // Redirect back with success message
            Toastr::success('Notification successfully added', 'success', ["positionClass" => "toast-top-right"]);
            return redirect("notification");
        }

        // If the notification wasn't created, redirect with error
        Toastr::error("Failed to add notification", 'Error', ["positionClass" => "toast-top-center"]);
        return redirect()->back();

    }

    public function fetch_notification_alert(Request $request){

        $validate = Validator::make($request->all(), [
            'receiver_id'   => 'required',
            'is_seen'       => 'required|integer',
        ]);

        if($validate->fails()){
            return $validate->errors();
        }

        $receiverId = $request->receiver_id;
        $isSeen = $request->is_seen;
        $cc_id = Session::get('cc_id');
        // If is_seen is 0, fetch the notification
        if ($isSeen == 0) {
            $notificationReceiver = CC_Notifiables::join('cc_notifications', 'cc_notifications.id', '=', 'cc_notifiables.notification_id')
                ->where('cc_notifiables.cc_id',$cc_id)
                ->where('cc_notifiables.reciever_id', $receiverId)
                ->where('cc_notifiables.status', 1)
                ->where('cc_notifiables.is_seen', 0)
                ->select('cc_notifications.text', 'cc_notifiables.id')
                ->first();

            // If a notification exists, return the data
            if ($notificationReceiver) {
                CC_Notifiables::where('reciever_id', $receiverId)
                ->where('id', $notificationReceiver->id)
                ->where('cc_id',$cc_id)
                ->update([
                    'is_seen' => 1,
                    'seen_at' => Carbon::now(),
                ]);
                return response()->json($notificationReceiver);
            }

            // If no notifications found, return 0
            return response()->json(0);
        }

        // If anything goes wrong, return an error
        return response()->json(['error' => 'Failed to process request.'], 400);
    }


}
