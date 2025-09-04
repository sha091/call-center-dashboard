<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GenrateOutboundController extends Controller
{
    public function home(){
        return view('outboundCalls');
    }

    public function genrateCall(Request $request){
        $validate = Validator::make($request->all(), [
            'caller_id' =>  ['required', 'regex:/^0\d{10}$/']
        ]);

        if($validate->fails()){
            Toastr::error('The phone number must be 11 digits long and start with 0', 'Error', ["positionClass" => "toast-top-center"]);
            return redirect("outbound");
        }
        $caller_id  = $request->caller_id;
        $site_root  = "storage/app/public";
        $command    = "cat $site_root/templates/outgoing.template";
        $logline    = shell_exec($command);

        $logline    = str_replace("<caller_id>",$caller_id,$logline);
        $logline    = str_replace("<agent_exten>",Session::get('AgentExten'),$logline);
        $logline    = str_replace("<agent_id>",Session::get('admin_id'),$logline);
        $logline    = str_replace("<key_selection>","0",$logline);
        $logline    = str_replace("<lang>","ur",$logline);
        $logline    = str_replace("<call_type>","OUTBOUND",$logline);
        $logline    = str_replace("<cc_id>",Session::get('cc_id'),$logline);

        $LogPath = "$site_root/ioutgoing_spool/".now()->format('Y-m-d');

        if (!File::exists($LogPath)) {
            File::makeDirectory($LogPath, 0777, true);
        }

        $logfile    = $site_root . '/ioutgoing_spool/'.now()->format('Y-m-d').'/'. $caller_id . '_' . now()->format('dmYHms') . '.call';
        // Step 1: Open the log file in append mode and write to it
        if (!File::exists($logfile)) {
            // Create the file if it doesn't exist
            File::put($logfile, $logline);
        } else {
            // Append to the file if it already exists
            File::append($logfile, $logline);
        }

        // Step 2: Change the file permissions to 777 (insecure, use with caution)
        chmod($logfile, 0777);  // Be cautious with 777 permissions in production environments

        // Step 3: Copy the file to the outgoing directory
        $destination = '/var/spool/asterisk/outgoing/' . basename($logfile);

        // Use PHP's copy function
        if (File::exists($logfile)) {
            copy($logfile, $destination);
        } else {
            // Handle file not found error
            die("Logfile does not exist.");
        }

        // Step 4: If you need to run the `cp` shell command, you can do it like this (optional)
        $cmd = "cp -f $logfile $destination";

        Toastr::success('Call Generating... soon you will recive a call back', 'success', ["positionClass" => "toast-top-center"]);
        if(auth()->user()->designation == 'Supervisor'){
            return redirect("outbound");
        }
        return redirect("Agent/outbound");
    }

    public function transferCall(Request $request){
        $validate = Validator::make($request->all(), [
            'caller_id' =>  ['required']
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            if(auth()->user()->designation == 'Supervisor'){
                return redirect("callAgent");
            }
            return redirect()->back();
        }
        $caller_id  = $request->caller_id;
        $site_root  = "storage/app/public";
        $command    = "cat $site_root/templates/outgoing_transfer.template";
        $logline    = shell_exec($command);

        $logline    = str_replace("<caller_id>",$caller_id,$logline);
        $logline    = str_replace("<agent_exten>",Session::get('AgentExten'),$logline);
        $logline    = str_replace("<agent_id>",Session::get('admin_id'),$logline);
        $logline    = str_replace("<key_selection>","0",$logline);
        $logline    = str_replace("<lang>","ur",$logline);
        $logline    = str_replace("<unique_id_new>",Session::get('unique_id'),$logline);
        $logline    = str_replace("<customer_number>",Session::get('caller_id'),$logline);
        $logline    = str_replace("<call_type>","OUTBOUND",$logline);
        $logline    = str_replace("<cc_id>",Session::get('cc_id'),$logline);

        $LogPath = "$site_root/ioutgoing_spool/".now()->format('Y-m-d');

        if (!File::exists($LogPath)) {
            File::makeDirectory($LogPath, 0777, true);
        }

        $logfile    = $site_root . '/ioutgoing_spool/'.now()->format('Y-m-d').'/'. $caller_id . '_' . now()->format('dmYHms') . '.call';
        // Step 1: Open the log file in append mode and write to it
        if (!File::exists($logfile)) {
            // Create the file if it doesn't exist
            File::put($logfile, $logline);
        } else {
            // Append to the file if it already exists
            File::append($logfile, $logline);
        }

        // Step 2: Change the file permissions to 777 (insecure, use with caution)
        chmod($logfile, 0777);  // Be cautious with 777 permissions in production environments

        // Step 3: Copy the file to the outgoing directory
        $destination = '/var/spool/asterisk/outgoing/' . basename($logfile);

        // Use PHP's copy function
        if (File::exists($logfile)) {
            copy($logfile, $destination);
        } else {
            // Handle file not found error
            die("Logfile does not exist.");
        }

        // Step 4: If you need to run the `cp` shell command, you can do it like this (optional)
        $cmd = "cp -f $logfile $destination";

        Toastr::success('Call Transferring... soon!', 'success', ["positionClass" => "toast-top-center"]);

        if(auth()->user()->designation == 'Supervisor'){
            return redirect("callAgent");
        }
        return  redirect()->back();
    }
}
