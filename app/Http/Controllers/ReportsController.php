<?php

namespace App\Http\Controllers;


use DateTime;
use Carbon\Carbon;
use App\Models\CC_Admin;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use App\Models\CC_Queue_Stats;
use App\Models\CC_Crm_Activity;
use Yajra\DataTables\DataTables;
use App\Models\CC_Login_Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{

    public function call_records(Request $request){
        $startDate      = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate        = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $search_keyword = isset($request->search_keyword)?$request->search_keyword:"caller_id";
        $keywords       = isset($request->keywords)?$request->keywords:false;
        $cc_id          = Session::get('cc_id');
        $query = DB::table('cc_xvu_queue_stats AS cdr')
                ->select(
                    DB::raw('DISTINCT cdr.caller_id'),
                    DB::raw('cdr.call_status'),
                    DB::raw('cdr.call_type'),
                    DB::raw('cdr.unique_id'),
                    DB::raw('DATE_FORMAT(call_date, "%m-%d-%Y") AS call_date'),
                    DB::raw('DATE_FORMAT(call_time, "%h:%i:%s %p") AS call_time'),
                    DB::raw('IFNULL(talk_time, "00:00:00") AS call_duration'),
                    DB::raw('IFNULL(cdr.full_name, "") AS full_name'),
                    DB::raw('IFNULL(cdr.staff_id, "") AS staff_id'),
                    DB::raw('cdr.userfield'),
                    DB::raw('cdr.id'),
                    DB::raw('IFNULL(talk_time, "00:00:00") AS talk_time')
                )->where('cc_id',$cc_id);

            // Check if a search keyword is provided
        if (!empty($search_keyword) && empty($keywords) && $search_keyword !== "caller_id" && $search_keyword !== "unique_id") {
            if ($search_keyword == "INBOUND") {
                $query->where('cdr.call_status', 'ANSWERED')
                    ->where('cdr.call_type', $search_keyword);
            } elseif ($search_keyword == "DROP") {
                $query->where('cdr.call_status', $search_keyword)
                    ->where('cdr.call_type', 'INBOUND');
            } else {
                $query->where(function($q) use ($search_keyword) {
                    $q->where('cdr.call_status', $search_keyword)
                    ->orWhere('cdr.call_type', $search_keyword);
                });
            }
        }else{
            $query->where('cdr.call_status','!=','TRANSFER');
        }

        // Check if both search_keyword and keywords are provided
        if (!empty($search_keyword) && !empty($keywords)) {
            $query->where("cdr.$search_keyword", $keywords);
        }

        // Check if fdate and tdate are provided for date range filtering
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween(DB::raw('DATE(cdr.call_datetime)'), [$startDate, $endDate]);
        } else {
            // Default to today's date if no date range is provided
            $query->whereDate('cdr.call_datetime', today());
        }
        $query->orderBy('cdr.call_datetime','desc');
        // Get the results
        $cdrs = $query->paginate(10);
        foreach($cdrs as $value){
            $call_date = Carbon::createFromFormat('m-d-Y', $value->call_date);
            if($value->call_type == "OUTBOUND"){
                $value->userfield   = env('AUDIO_BASE_URL')."$cc_id/outbound/".$call_date->format('Ymd')."/$value->userfield.wav";
            }
            if($value->call_type == "INBOUND"){
                $value->userfield   = env('AUDIO_BASE_URL')."$cc_id/inbound/".$call_date->format('Ymd')."/$value->userfield.wav";
            }
        }
        return view('callRecord',compact('cdrs'));
    }

    public function export_call_records(Request $request){
        $startDate      = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate        = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $search_keyword = isset($request->search_keyword)?$request->search_keyword:"caller_id";
        $keywords       = isset($request->keywords)?$request->keywords:false;
        $cc_id          = Session::get('cc_id');
        $query = DB::table('cc_xvu_queue_stats AS cdr')
                ->select(
                    DB::raw('DISTINCT cdr.caller_id'),
                    DB::raw('cdr.call_status'),
                    DB::raw('cdr.call_type'),
                    DB::raw('cdr.unique_id'),
                    DB::raw('DATE_FORMAT(call_date, "%m-%d-%Y") AS call_date'),
                    DB::raw('DATE_FORMAT(call_time, "%h:%i:%s %p") AS call_time'),
                    DB::raw('IFNULL(talk_time, "00:00:00") AS call_duration'),
                    DB::raw('IFNULL(cdr.full_name, "") AS full_name'),
                    DB::raw('IFNULL(cdr.staff_id, "") AS staff_id'),
                    DB::raw('cdr.userfield'),
                    DB::raw('cdr.id'),
                    DB::raw('IFNULL(talk_time, "00:00:00") AS talk_time')
                )->where('cc_id',$cc_id);

            // Check if a search keyword is provided
        if (!empty($search_keyword) && empty($keywords) && $search_keyword !== "caller_id" && $search_keyword !== "unique_id") {
            if ($search_keyword == "INBOUND") {
                $query->where('cdr.call_status', 'ANSWERED')
                    ->where('cdr.call_type', $search_keyword);
            } elseif ($search_keyword == "DROP") {
                $query->where('cdr.call_status', $search_keyword)
                    ->where('cdr.call_type', 'INBOUND');
            } else {
                $query->where(function($q) use ($search_keyword) {
                    $q->where('cdr.call_status', $search_keyword)
                    ->orWhere('cdr.call_type', $search_keyword);
                });
            }
        }

        // Check if both search_keyword and keywords are provided
        if (!empty($search_keyword) && !empty($keywords)) {
            $query->where("cdr.$search_keyword", $keywords);
        }

        // Check if fdate and tdate are provided for date range filtering
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween(DB::raw('DATE(cdr.call_datetime)'), [$startDate, $endDate]);
        } else {
            // Default to today's date if no date range is provided
            $query->whereDate('cdr.call_datetime', today());
        }
        $query->orderBy('cdr.call_datetime','desc');
        // Get the results
        $cdrs =  $query->get();
        foreach($cdrs as $value){
            $call_date = Carbon::createFromFormat('m-d-Y', $value->call_date);
            if($value->call_type == "OUTBOUND"){
                $value->userfield   = env('AUDIO_BASE_URL')."$cc_id/outbound/".$call_date->format('Ymd')."/$value->userfield.wav";
            }
            if($value->call_type == "INBOUND"){
                $value->userfield   = env('AUDIO_BASE_URL')."$cc_id/inbound/".$call_date->format('Ymd')."/$value->userfield.wav";
            }
        }
        $fileName = 'Call-Records.csv';

        $filePath = "csv/$fileName";

        // Open the output stream
        $handle = Storage::disk('public')->put($filePath, ''); // Create an empty file at the location

        // Open the file for writing CSV data
        $handle = fopen(storage_path('app/public/' . $filePath), 'w');

        // Add the header row to CSV
        fputcsv($handle,array('Caller Id','Date','Time','Duration','Agent Name','Call Id','Audio'));
        foreach ($cdrs as $row) {
            $row = array(
                "caller_id"         => $row->caller_id,
                "call_date"         => $row->call_date,
                "call_time"         => $row->call_time,
                "call_duration"     => $row->call_duration,
                "full_name"         => $row->full_name,
                "unique_id"         => $row->unique_id,
                'audio'             => $row->userfield
            );
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        $url = asset('storage/app/public/' . $filePath);
        // Return the CSV file for download
        return response()->json([
            'success'   => true,
            'message' => 'CSV file created successfully!',
            'download_url' => $url // Return the file path
        ]);


    }

    public function outbound_records(Request $request){

        $admin_id       = isset($request->admin_id) ? $request->admin_id : false;
        $startDate      = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate        = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $cc_id          = Session::get('cc_id');
        $cdrs = cc_queue_stats::from('cc_queue_stats AS stats')
        ->select(
            'admin.admin_id',
            'admin.full_name',
            DB::raw('DATE(stats.update_datetime) AS call_date'),
            DB::raw('TIME(stats.staff_start_datetime) AS TIME'),
            'stats.staff_end_datetime',
            'stats.staff_start_datetime',
            'stats.caller_id',
            'stats.call_type',
            'stats.call_status',
            'wk.detail AS remarks'
        )
        ->join('cc_admin AS admin', 'admin.admin_id', '=', 'stats.staff_id')
        ->leftJoin('cc_vu_workcodes AS wk', function ($join) {
            $join->on('stats.caller_id', '=', 'wk.caller_id')
                 ->on('stats.unique_id', '=', 'wk.unique_id');
        })
        ->where('admin.cc_id',$cc_id)
        ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
        ->where('stats.call_type', 'OUTBOUND')
        ->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('admin.admin_id', '=', $admin_id);
            }
        })->where('stats.call_status', 'ANSWERED')
        ->groupBy('stats.unique_id')
        ->orderBy('stats.id', 'DESC')
        ->paginate(10);

        $agentDropDown = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->get();
        return view('outboundRecords',compact('cdrs','agentDropDown'));

    }

    public function export_outbound_records(Request $request){
        $admin_id       = isset($request->admin_id) ? $request->admin_id : false;
        $startDate      = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate        = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $cc_id          = Session::get('cc_id');
        $cdrs = cc_queue_stats::from('cc_queue_stats AS stats')
        ->select(
            'admin.admin_id',
            'admin.full_name',
            DB::raw('DATE(stats.update_datetime) AS call_date'),
            DB::raw('TIME(stats.staff_start_datetime) AS TIME'),
            'stats.staff_end_datetime',
            'stats.staff_start_datetime',
            'stats.caller_id',
            'stats.call_type',
            'stats.call_status',
            'wk.detail AS remarks'
        )
        ->join('cc_admin AS admin', 'admin.admin_id', '=', 'stats.staff_id')
        ->leftJoin('cc_vu_workcodes AS wk', function ($join) {
            $join->on('stats.caller_id', '=', 'wk.caller_id')
                 ->on('stats.unique_id', '=', 'wk.unique_id');
        })
        ->where('admin.cc_id',$cc_id)
        ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
        ->where('stats.call_type', 'OUTBOUND')
        ->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('admin.admin_id', '=', $admin_id);
            }
        })->where('stats.call_status', 'ANSWERED')
        ->groupBy('stats.unique_id')
        ->orderBy('stats.id', 'DESC')
        ->get();

        $fileName = 'Outbound-Records.csv';

        $filePath = "csv/$fileName";

        // Open the output stream
        $handle = Storage::disk('public')->put($filePath, ''); // Create an empty file at the location

        // Open the file for writing CSV data
        $handle = fopen(storage_path('app/public/' . $filePath), 'w');

        // Add the header row to CSV
        fputcsv($handle,array('AGENT NAME','CALL DATE','Time','Duration','CALLER ID','CALL TYPE','REMARKS'));
        foreach ($cdrs as $row) {
            $row = array(
                "full_name"         => $row->full_name,
                "call_date"         => $row->call_date,
                "TIME"              => ReportsController::convertToAmPm($row->TIME),
                "call_duration"     => ReportsController::differenceInDays($row->staff_start_datetime,$row->staff_end_datetime),
                "caller_id"         => $row->caller_id,
                "call_type"         => $row->call_type,
                'remarks'           => $row->remarks
            );
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        $url = asset('storage/app/public/' . $filePath);
        // Return the CSV file for download
        return response()->json([
            'success'   => true,
            'message' => 'CSV file created successfully!',
            'download_url' => $url // Return the file path
        ]);


    }

    public function inbound_records(Request $request){
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $cc_id      = Session::get('cc_id');
        $cdrs = cc_queue_stats::from('cc_queue_stats AS stats')
        ->select(
            'admin.admin_id',
            'admin.full_name',
            DB::raw('DATE(stats.update_datetime) AS call_date'),
            DB::raw('TIME(stats.staff_start_datetime) AS TIME'),
            'stats.staff_end_datetime',
            'stats.staff_start_datetime',
            'stats.caller_id',
            'stats.call_type',
            'stats.call_status',
            'wk.detail AS remarks'
        )
        ->join('cc_admin AS admin', 'admin.admin_id', '=', 'stats.staff_id')
        ->leftJoin('cc_vu_workcodes AS wk', function ($join) {
            $join->on('stats.caller_id', '=', 'wk.caller_id')
                 ->on('stats.unique_id', '=', 'wk.unique_id');
        })
        ->where('admin.cc_id',$cc_id)
        ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
        ->where('stats.call_type', 'INBOUND')
        ->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('admin.admin_id', '=', $admin_id);
            }
        })->where('stats.call_status', 'ANSWERED')
        ->groupBy('stats.unique_id')
        ->orderBy('stats.id', 'DESC')
        ->paginate(10);

        $agentDropDown = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->get();
        return view('inboundRecords',compact('cdrs','agentDropDown'));
    }

    public function export_inbound_records(Request $request){
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $cc_id      = Session::get('cc_id');
        $cdrs = cc_queue_stats::from('cc_queue_stats AS stats')
        ->select(
            'admin.admin_id',
            'admin.full_name',
            DB::raw('DATE(stats.update_datetime) AS call_date'),
            DB::raw('TIME(stats.staff_start_datetime) AS TIME'),
            'stats.staff_end_datetime',
            'stats.staff_start_datetime',
            'stats.caller_id',
            'stats.call_type',
            'stats.call_status',
            'wk.detail AS remarks'
        )
        ->join('cc_admin AS admin', 'admin.admin_id', '=', 'stats.staff_id')
        ->leftJoin('cc_vu_workcodes AS wk', function ($join) {
            $join->on('stats.caller_id', '=', 'wk.caller_id')
                 ->on('stats.unique_id', '=', 'wk.unique_id');
        })
        ->where('admin.cc_id',$cc_id)
        ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
        ->where('stats.call_type', 'INBOUND')
        ->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('admin.admin_id', '=', $admin_id);
            }
        })->where('stats.call_status', 'ANSWERED')
        ->groupBy('stats.unique_id')
        ->orderBy('stats.id', 'DESC')
        ->get();

        $fileName = 'Inbound-Records.csv';

        $filePath = "csv/$fileName";

        // Open the output stream
        $handle = Storage::disk('public')->put($filePath, ''); // Create an empty file at the location

        // Open the file for writing CSV data
        $handle = fopen(storage_path('app/public/' . $filePath), 'w');

        // Add the header row to CSV
        fputcsv($handle,array('AGENT NAME','CALL DATE','Time','Duration','CALLER ID','CALL TYPE','REMARKS'));
        foreach ($cdrs as $row) {
            $row = array(
                "full_name"         => $row->full_name,
                "call_date"         => $row->call_date,
                "TIME"              => ReportsController::convertToAmPm($row->TIME),
                "call_duration"     => ReportsController::differenceInDays($row->staff_start_datetime,$row->staff_end_datetime),
                "caller_id"         => $row->caller_id,
                "call_type"         => $row->call_type,
                'remarks'           => $row->remarks
            );
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        $url = asset('storage/app/public/' . $filePath);
        // Return the CSV file for download
        return response()->json([
            'success'   => true,
            'message' => 'CSV file created successfully!',
            'download_url' => $url // Return the file path
        ]);

    }

    public function agent_pd_home(Request $request){
        $cc_id = Session::get('cc_id');
        $agentDropDown = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->get();
        return view('agentProductivityReport',compact('agentDropDown'));
    }

    public function workingTime(Request $request){
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $cc_id      = Session::get('cc_id');
        $cdrs = CC_Login_Activity::select(
            DB::raw('COUNT(*) AS trec'),
            DB::raw('TIME(MIN(login_datetime)) AS login_time'),
            'staff_id',
            DB::raw('TIME(MAX(logout_datetime)) AS max_logout_time'),
            DB::raw('CASE WHEN TIME(MAX(login_datetime)) = TIME(MAX(logout_datetime)) THEN TIME(NOW()) ELSE TIME(MAX(logout_datetime)) END AS logout_time'),
            DB::raw('CASE WHEN TIME(MAX(login_datetime)) = TIME(MAX(logout_datetime)) THEN TIMEDIFF(NOW(), MIN(login_datetime)) ELSE TIMEDIFF(MAX(logout_datetime), MIN(login_datetime)) END AS duration')
        )->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('staff_id',$admin_id);
            }
        })
        ->where('cc_id',$cc_id)
        ->where('staff_id', '<>', '9035')
        ->whereDate('login_datetime', $startDate)
        ->whereDate('logout_datetime', $startDate)
        ->groupBy('staff_id')
        ->get();
        foreach($cdrs as $value){
            $agent_name = CC_Admin::where('admin_id',$value->staff_id)->first();
            $value->agent_name = $agent_name->full_name;
            $value->onlineTime = date("h:i:s A",strtotime($value->login_time));
            if($value->max_logout_time == $value->login_time){
                $value->is_colour = 'text-danger';
                $value->login_time = "Logged In";
            }else{
                $value->is_colour = 'text-dark';
                $value->login_time = date("h:i:s A",strtotime($value->logout_time));
            }
        }
        return response()->json($cdrs);
    }

    public function breakTimeSummary(Request $request){
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $cc_id      = Session::get('cc_id');
        $arr_names  = array('2'=>"Namaz Break",'3'=>"Lunch Break",'4'=>"Tea Break",'5'=>"Auxiliary Break",'6'=>"Campaign");
        $arr_values = array('2','3','4','5','7');
        $query = CC_Crm_Activity::select(
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_datetime, start_datetime)))) AS duration'),
            'STATUS as crm_status'
            )
            ->where('cc_id',$cc_id)
            ->where('staff_id', '<>', '9035') // Exclude staff_id 9035
            ->where('STATUS', '<>', 6) // Exclude STATUS 6
            ->where('STATUS', '<>', 1) // Exclude STATUS 1
            ->whereRaw('TIMEDIFF(end_datetime, start_datetime) <> "00:00:00"')
            ->where(function($query) use($admin_id){
                if($admin_id){
                    $query->where('staff_id',$admin_id);
                }
            })
            ->whereDate('update_datetime', $startDate)
            ->groupBy('STATUS')
            ->get();
        foreach($query as $value){
            if (in_array($value->crm_status, $arr_values)) {
                $value->crm_status = $arr_names[$value->crm_status];
            }else{
                $value->crm_status = '-';
            }
        }
        return response()->json($query);
    }

    public function onCallBuzyTime(Request $request){
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $cc_id      = Session::get('cc_id');
        $query = cc_queue_stats::select(
            DB::raw('COUNT(*) as cnt'),
            'staff_id',
            'call_type',
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(staff_end_datetime, staff_start_datetime)))) AS call_duration'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(update_datetime, staff_end_datetime)))) AS busy_duration')
        )
        ->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('staff_id',$admin_id);
            }
        })
        ->where('cc_id',$cc_id)
        ->where('staff_id', '<>', '9035')
        ->where('staff_id', '<>', '0')
        ->whereDate('update_datetime', $startDate)
        ->whereRaw('TIMEDIFF(staff_end_datetime, staff_start_datetime) <> "00:00:00"')
        ->groupBy('staff_id', 'call_type')
        ->get();
        foreach($query as $value){
            $agent_name = CC_Admin::where('admin_id',$value->staff_id)->first();
            $value->agent_name = $agent_name->full_name;
            $value->abandon_calls = DB::table('cc_abandon_calls')
                ->whereDate('update_datetime', $startDate)
                ->where('staff_id', $value->staff_id)
                ->count();
            if($value->call_type == "OUTBOUND"){
                $value->call_type = 'OUTGOING';
            }

        }
        return response()->json($query);
    }

    public function breakTimes(Request $request){
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $cc_id      = Session::get('cc_id');
        $result = CC_Crm_Activity::select(
            DB::raw('TIME(start_datetime) as start_time'),
            'staff_id',
            DB::raw('TIME(end_datetime) as end_time'),
            DB::raw('TIMEDIFF(end_datetime, start_datetime) as duration'),
            'STATUS as crm_status'
        )
        ->where(function($query) use($admin_id){
            if($admin_id){
                $query->where('staff_id',$admin_id);
            }
        })
        ->where('cc_id',$cc_id)
        ->where('staff_id', '<>', 9035)
        ->whereDate('update_datetime', $startDate)
        ->whereNotIn('STATUS', [6, 1])
        ->whereRaw('TIMEDIFF(end_datetime, start_datetime) <> "00:00:00"')
        ->get();
        foreach($result as $value){
            if($value->crm_status == 1){
                $value->status = "Online";
            }elseif($value->crm_status == 2){
                $value->status = "Namaz Break";
            }elseif($value->crm_status == 3){
                $value->status = "Lunch Break";
            }elseif($value->crm_status == 4){
                $value->status = "Tea Break";
            }elseif($value->crm_status == 5){
                $value->status = "Auxiliary Break";
            }elseif($value->crm_status == 6){
                $value->status = "Offline";
            }elseif($value->crm_status == 7){
                $value->status = "Campaign";
            }else{
                $value->status = "Unkown";
            }
            $agent_name = CC_Admin::where('admin_id',$value->staff_id)->first();
            $value->agent_name = $agent_name->full_name;
            $value->start_time = date("h:i:s A",strtotime($value->start_time));
            $value->end_time   = date("h:i:s A",strtotime($value->end_time));
        }
        return response()->json($result);
    }

    public function sum_the_time($time1, $time2) {
        $times = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time)
        {
              list($hour,$minute,$second) = explode(':', $time);
              $seconds += $hour*3600;
              $seconds += $minute*60;
              $seconds += $second;
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        //return "{$hours}:{$minutes}:{$seconds}";
    }

    public function abandonCalls(Request $request){
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $search_keyword = isset($request->search_keyword)?$request->search_keyword:"caller_id";
        $keywords       = isset($request->keywords)?$request->keywords:false;
        $cc_id          = Session::get('cc_id');
        $query = DB::table('cc_abandon_calls')
            ->select('cc_abandon_calls.caller_id',
                    'cc_abandon_calls.unique_id',
                    'cdr.full_name',
                    'cc_abandon_calls.staff_id',
                    'cc_queue_stats.userfield',
                    'cc_abandon_calls.update_datetime')
            ->join('cc_admin as cdr', 'cc_abandon_calls.staff_id', '=', 'cdr.admin_id')
            ->join('cc_queue_stats', 'cc_abandon_calls.unique_id', '=', 'cc_queue_stats.unique_id')
            ->where('cc_admin.cc_id',$cc_id)
            ->where('cc_queue_stats.call_type', '=', 'INBOUND');


        if (!empty($search_keyword) && !empty($keywords)) {
            if($search_keyword == "caller_id"){
                $query->where('cc_abandon_calls.' . $search_keyword, '=', $keywords);
            }else{
                $query->where('cdr.' . $search_keyword, '=', $keywords);
            }
        }

        // Date filters
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween(DB::raw('DATE(cc_abandon_calls.update_datetime)'), [$startDate, $endDate]);
        } else {
            $query->whereDate('cc_abandon_calls.update_datetime', '=', Carbon::today());
        }

        // Group by and order
        $query->groupBy('cc_abandon_calls.id', 'cc_abandon_calls.unique_id')
            ->orderBy('cc_abandon_calls.update_datetime',"desc"); // assuming $order is 'asc' or 'desc'

        // Execute the query and get the results
        $cdrs = $query->paginate(10);
        return view('abandonRecords',compact('cdrs'));
    }

    public function misscallRecords(Request $request){        
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $agent_id   = isset($request->agent_id)?$request->agent_id:false;
        $cc_id      = Session::get('cc_id');
        if(auth()->user()->designation != 'Supervisor'){
            $agent_id   = isset($request->agent_id)?$request->agent_id:Session::get('admin_id');
        }
        $agentDropDown = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->get();
        $cdrs = DB::table('cc_queue_stats as stats')
            ->select(
                'stats.staff_id',
                DB::raw('DATE(stats.update_datetime) as call_date'),
                DB::raw('TIME(stats.update_datetime) as TIME'),
                'stats.staff_end_datetime',
                'stats.call_datetime',
                'stats.caller_id',
                'stats.call_type',
                'stats.call_status',
                'stats.unique_id as call_id',
            )
            ->where('stats.cc_id',$cc_id)
            ->where(function($query) use($agent_id){
                if($agent_id){
                    $query->where('stats.staff_id', $agent_id);
                }
            })
            ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
            ->whereIn('stats.call_status', ['MISSED CALL','IVR'])                    
            ->paginate(10);
        foreach($cdrs as $value){
            if(!empty($value->staff_id)){
                $get_full_name = Cc_Admin::where('admin_id',$value->staff_id)->first();
                $value->full_name = $get_full_name->full_name;
            }
        }
        return view('missedCallRecords',compact('agentDropDown','cdrs'));
    }

    public function exportMisscallRecords(Request $request){
        $agentDropDown = CC_Admin::where('designation','Agents')->get();
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $agent_id   = isset($request->agent_id)?$request->agent_id:false;
        $cc_id      = Session::get('cc_id');
        if(auth()->user()->designation != 'Supervisor'){
            $agent_id   = isset($request->agent_id)?$request->agent_id:Session::get('admin_id');
        }
        $cdrs = DB::table('cc_queue_stats as stats')
            ->select(
                'stats.staff_id',
                DB::raw('DATE(stats.update_datetime) as call_date'),
                DB::raw('TIME(stats.update_datetime) as TIME'),
                'stats.staff_end_datetime',
                'stats.call_datetime',
                'stats.caller_id',
                'stats.call_type',
                'stats.call_status',
                'stats.unique_id as call_id'
            )
            ->where('stats.cc_id',$cc_id)
            ->where(function($query) use($agent_id){
                if($agent_id){
                    $query->where('stats.staff_id', $agent_id);
                }
            })
            ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
            ->whereIn('stats.call_status', ['MISSED CALL','IVR']) 
            ->get();
        foreach($cdrs as $value){
            if(!empty($value->staff_id)){
                $get_full_name = Cc_Admin::where('admin_id',$value->staff_id)->first();
                $value->full_name = $get_full_name->full_name;
            }
        }
        $fileName = 'Misscall-Records.csv';

        $filePath = "csv/$fileName";

        // Open the output stream
        $handle = Storage::disk('public')->put($filePath, ''); // Create an empty file at the location

        // Open the file for writing CSV data
        $handle = fopen(storage_path('app/public/' . $filePath), 'w');

        // Add the header row to CSV
        fputcsv($handle,array('AGENT NAME','CALL DATE','Time','Duration','CALLER ID','CALL ID'));
        foreach ($cdrs as $row) {

            $row = array(
                "full_name"         => $row->full_name,
                "call_date"         => $row->call_date,
                "TIME"              => ReportsController::convertToAmPm($row->TIME),
                "call_duration"     => ReportsController::differenceInDays($row->staff_end_datetime,$row->call_datetime),
                "caller_id"         => $row->caller_id,
                "call_id"           => $row->call_id,
            );
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        $url = asset('storage/app/public/' . $filePath);
        // Return the CSV file for download
        return response()->json([
            'success'   => true,
            'message' => 'CSV file created successfully!',
            'download_url' => $url // Return the file path
        ]);
    }

    public function transferredCallsReport(Request $request){        
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $agent_id   = isset($request->agent_id)?$request->agent_id:false;
        $cc_id      = Session::get('cc_id');
        if(auth()->user()->designation != 'Supervisor'){
            $agent_id   = isset($request->agent_id)?$request->agent_id:Session::get('admin_id');
        }
        $agentDropDown = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->get();
        $cdrs = DB::table('cc_queue_stats as stats')
                ->select(
                    'admin.full_name',
                    DB::raw('DATE(stats.update_datetime) as call_date'),
                    DB::raw('TIME(stats.update_datetime) as TIME'),
                    'stats.staff_end_datetime',
                    'stats.call_datetime',
                    'stats.caller_id',
                    'stats.call_type',
                    'stats.call_status',
                    'stats.unique_id as call_id',
                    'admin2.full_name as agent_name'
                )
                ->leftJoin('cc_admin as admin', 'admin.admin_id', '=', 'stats.staff_id')
                ->leftJoin('cc_admin as admin2', 'admin2.admin_id', '=', 'stats.account_no')
                ->where('admin.cc_id',$cc_id)
                ->where(function($query) use($agent_id){
                    if($agent_id){
                        $query->where('stats.staff_id', $agent_id);
                    }
                })
                ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
                ->where('stats.call_status', '=', 'TRANSFER')
                ->orderByDesc('stats.id')
                ->paginate(10);
        return view('transferredCallRecords',compact('agentDropDown','cdrs'));
    }

    public function ExportTransferredCallsReport(Request $request){
        $agentDropDown = CC_Admin::where('designation','Agents')->get();
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $agent_id   = isset($request->agent_id)?$request->agent_id:false;
        $cc_id      = Session::get('cc_id');
        $cdrs = DB::table('cc_queue_stats as stats')
                ->select(
                    'admin.full_name',
                    DB::raw('DATE(stats.update_datetime) as call_date'),
                    DB::raw('TIME(stats.update_datetime) as TIME'),
                    'stats.staff_end_datetime',
                    'stats.call_datetime',
                    'stats.caller_id',
                    'stats.call_type',
                    'stats.call_status',
                    'stats.unique_id as call_id',
                    'admin2.full_name as agent_name'
                )
                ->leftJoin('cc_admin as admin', 'admin.admin_id', '=', 'stats.staff_id')
                ->leftJoin('cc_admin as admin2', 'admin2.admin_id', '=', 'stats.account_no')
                ->where('admin.cc_id',$cc_id)
                ->where(function($query) use($agent_id){
                    if($agent_id){
                        $query->where('stats.staff_id', $agent_id);
                    }
                })
                ->whereBetween(DB::raw('DATE(stats.update_datetime)'), [$startDate, $endDate])
                ->where('stats.call_status', '=', 'TRANSFER')
                ->orderByDesc('stats.id')
                ->get();

        $fileName = 'Transferred-Calls-Records.csv';

        $filePath = "csv/$fileName";

        // Open the output stream
        $handle = Storage::disk('public')->put($filePath, ''); // Create an empty file at the location

        // Open the file for writing CSV data
        $handle = fopen(storage_path('app/public/' . $filePath), 'w');

        // Add the header row to CSV
        fputcsv($handle,array('Transferred By','Transferred To','Call Date','Time','Duration','CALLER ID','CALL ID'));
        foreach ($cdrs as $row) {

            $row = array(
                "agent_name"        => $row->agent_name,
                "full_name"         => $row->full_name,
                "call_date"         => date('d-m-Y', strtotime($row->call_date)),
                "TIME"              => date('h:i:s A', strtotime($row->TIME)),
                "call_duration"     => ReportsController::differenceInDays($row->staff_end_datetime,$row->call_datetime),
                "caller_id"         => $row->caller_id,
                "call_id"           => $row->call_id,
            );
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        $url = asset('storage/app/public/' . $filePath);
        // Return the CSV file for download
        return response()->json([
            'success'   => true,
            'message' => 'CSV file created successfully!',
            'download_url' => $url // Return the file path
        ]);
    }

    public function agentSummaryReport(Request $request){
        $startDate      = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate        = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $admin_id       = isset($request->admin_id)?$request->admin_id:false;
        $cc_id          = Session::get('cc_id');
        if(auth()->user()->designation != 'Supervisor'){
            $admin_id   = isset($request->admin_id)?$request->admin_id:Session::get('admin_id');
        }
        $cdrs = cc_queue_stats::from('cc_queue_stats AS stats')
        ->select(
            'cc_admin.full_name',
            'stats.staff_id',
            DB::raw('DATE(stats.call_datetime) as call_datetime'),
            DB::raw('COUNT(*) AS attempted_calls'),
            DB::raw("SUM(CASE WHEN stats.call_status = 'ANSWERED' THEN 1 ELSE 0 END) AS answered_calls")
        )
        ->join('cc_admin', 'cc_admin.admin_id', '=', 'stats.staff_id')
        ->where('cc_admin.cc_id',$cc_id)
        ->whereNotNull('stats.staff_id')
        ->when($admin_id, function ($query, $admin_id) {
            return $query->where('stats.staff_id', $admin_id);
        })
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween(DB::raw('DATE(stats.call_datetime)'), [$startDate, $endDate]);
        })
        ->groupBy('stats.staff_id', DB::raw('DATE(stats.call_datetime)'))
        ->orderByDesc(DB::raw('DATE(stats.call_datetime)'))
        ->paginate(10);
        return view('agentSummaryReport',compact('cdrs'));
    }

    public function differenceInDays($date1, $date2) {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);

        // Calculate the difference
        $difference = $date2->diff($date1);

        // Extract the difference in days, hours, minutes, and seconds
        $diffInDays = $difference->days; // Total days
        $diffInHours = $difference->h; // Remaining hours
        $diffInMinutes = $difference->i; // Remaining minutes
        $diffInSeconds = $difference->s; // Remaining seconds

        // Format hours, minutes, and seconds to be two digits
        $diffInHours = str_pad($diffInHours, 2, "0", STR_PAD_LEFT);
        $diffInMinutes = str_pad($diffInMinutes, 2, "0", STR_PAD_LEFT);
        $diffInSeconds = str_pad($diffInSeconds, 2, "0", STR_PAD_LEFT);

        return "$diffInHours:$diffInMinutes:$diffInSeconds";
    }

    public function convertToAmPm($time) {
        // Convert the input time to a Unix timestamp
        $timestamp = strtotime($time);

        // Format the timestamp to AM/PM format
        return date('h:i:s A', $timestamp);
    }

    public function off_time_report(Request $request){
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $admin_id   = isset($request->admin_id) ? $request->admin_id : false;
        $cc_id      = Session::get('cc_id');
        $cdrs = DB::table('ci_off_time_stats')->select(
                'caller_id',
                'call_status',
                DB::raw("DATE_FORMAT(call_datetime, '%b %d %Y %h:%i %p') as call_date")
            )
        ->where('cc_id',$cc_id)
        ->whereBetween(DB::raw('DATE(call_datetime)'), [$startDate, $endDate])
        ->where('call_status', 'OFFTIME')
	->paginate(10);

        return view('offTimeStats',compact('cdrs'));

    }

    public function feedback_report(Request $request){        
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $agent_id   = isset($request->agent_id)?$request->agent_id:false;
        $cc_id      = Session::get('cc_id');
        $agentDropDown = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->get();
        $cdrs = DB::table('cc_queue_stats')
        ->select('cc_queue_stats.*', 'cc_admin.full_name')
        ->join('cc_admin', 'cc_admin.admin_id', '=', 'cc_queue_stats.staff_id')
        ->where(function($query) use($agent_id){
            if($agent_id){
                $query->where('cc_queue_stats.staff_id', $agent_id);
            }
        })
        ->where('cc_queue_stats.cc_id',$cc_id)
        ->where('cc_queue_stats.is_feedback','!=','')
        ->whereBetween(DB::raw('DATE(`call_datetime`)'), [$startDate, $endDate])
        ->orderByDesc('call_datetime')->paginate(10);
        return view('feedbackReport',compact('agentDropDown','cdrs'));
    }

    public function export_feedback_report(Request $request){
        $agentDropDown = CC_Admin::where('designation','Agents')->get();
        $startDate  = isset($request->startDate)?$request->startDate:date('Y-m-d');
        $endDate    = isset($request->endDate)?$request->endDate:date('Y-m-d');
        $agent_id   = isset($request->agent_id)?$request->agent_id:false;
        $cc_id      = Session::get('cc_id');
        $cdrs = DB::table('cc_queue_stats')
        ->select('cc_queue_stats.*', 'cc_admin.full_name')
        ->join('cc_admin', 'cc_admin.admin_id', '=', 'cc_queue_stats.staff_id')
        ->where(function($query) use($agent_id){
            if($agent_id){
                $query->where('cc_queue_stats.staff_id', $agent_id);
            }
        })
        ->where('cc_queue_stats.cc_id',$cc_id)
        ->where('cc_queue_stats.is_feedback','!=','')
        ->whereBetween(DB::raw('DATE(`call_datetime`)'), [$startDate, $endDate])
        ->orderByDesc('call_datetime')->get();

        $fileName = 'Feedback-Records.csv';

        $filePath = "csv/$fileName";

        // Open the output stream
        $handle = Storage::disk('public')->put($filePath, ''); // Create an empty file at the location

        // Open the file for writing CSV data
        $handle = fopen(storage_path('app/public/' . $filePath), 'w');

        // Add the header row to CSV
        fputcsv($handle,array('Customer Number','Agent Name','Response','Call Date'));
        foreach ($cdrs as $row) {

            $row = array(
                "msisdn"            => $row->caller_id,
                "full_name"         => $row->full_name,
                "response"          => $row->is_feedback == 1 ? "Satisfactory" : "Unsatisfactory",
                "datetime"          => date('Y-m-d h:i A', strtotime($row->call_datetime))
            );
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        $url = asset('storage/app/public/' . $filePath);
        // Return the CSV file for download
        return response()->json([
            'success'   => true,
            'message' => 'CSV file created successfully!',
            'download_url' => $url // Return the file path
        ]);


    }
}
