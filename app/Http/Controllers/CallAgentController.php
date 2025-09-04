<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CC_Admin;
use Illuminate\Http\Request;
use App\Models\CC_Queue_Stats;
use App\Models\CC_Crm_Activity;
use Yajra\DataTables\DataTables;
use App\Models\CC_Login_Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CallAgentController extends Controller
{

    public function home(){
        return view('callAgentStats');
    }

    public function agent_stats(Request $request){
        $cc_id = Session::get('cc_id');
        $data = CC_Admin::select(
            'admins.admin_id',
            'admins.full_name',
            'admins.email',
            'admins.agent_exten',
            'admins.is_crm_login',
            'admins.is_phone_login',
            'admins.is_busy',
            'admins.unique_id',
            'queue.caller_id',
            'queue.call_type',
            DB::raw("CASE admins.is_busy
                WHEN '1' THEN TIMEDIFF(TIME(NOW()), TIME(queue.staff_start_datetime))
                WHEN '0' THEN TIMEDIFF(TIME(NOW()), TIME(queue.update_datetime))
                WHEN '2' THEN TIMEDIFF(TIME(NOW()), TIME(queue.update_datetime))
                WHEN '3' THEN TIMEDIFF(TIME(NOW()), TIME(queue.update_datetime))
                END AS t_duration")
        )->from('cc_admin AS admins')
        ->leftJoin('cc_queue_stats AS queue', 'admins.unique_id', '=', 'queue.unique_id')
        ->where('admins.designation', 'Agents')
        ->where('admins.cc_id',$cc_id)
        ->where('admins.status', 1)
        ->groupBy('admins.full_name')
        ->orderBy('admins.full_name')
        ->get();
        foreach($data as $value){
            $position = strpos($value->t_duration, "-");
            if($value->is_busy == 2){
                $value->is_colour = 'text-success';
                $value->call_status = "Ringing";
                $value->t_duration = "";
            }elseif($value->is_busy == 1){
                $value->is_colour = 'text-success';
                if($value->call_type == "OUTBOUND"){
                    $value->call_status = "On Call (O.B)";
                }else{
                    $value->call_status = "On Call (I.B)";
                }
                if($position === false){
                    $value->t_duration = $value->t_duration;
                }else{
                    $value->t_duration = "";
                }
            }elseif($value->is_busy == 3){
                $value->is_colour = 'text-danger';
                $value->call_status = "Busy";
                if($position === false){
                    $value->t_duration = $value->t_duration;
                }else{
                    $value->t_duration = "";
                }
            }elseif($value->is_busy == 0){
                $value->is_colour = 'text-dark';
                $value->call_status = "Free";
                if($position === false){
                    $value->t_duration = $value->t_duration;
                }else{
                    $value->t_duration = "";
                }
            }

            if($value->is_crm_login == 1){
                $value->login_status = "Online";
            }elseif($value->is_crm_login == 2){
                $value->login_status = "Namaz Break";
            }elseif($value->is_crm_login == 3){
                $value->login_status = "Lunch Break";
            }elseif($value->is_crm_login == 4){
                $value->login_status = "Tea Break";
            }elseif($value->is_crm_login == 5){
                $value->login_status = "Auxiliary Break";
            }elseif($value->is_crm_login == 6){
                $value->login_status = "Assignment";
            }elseif($value->is_crm_login == 7){
                $value->login_status = "Campaign";
            }elseif($value->is_crm_login == 0){
                $value->login_status = "Offline";
                $value->t_duration = "";
            }else{
                $value->login_status = "Unkown";
            }
            $value->crm_status = CallAgentController::crm_status_time($value->admin_id,$cc_id);
            $value->busy_time  = CallAgentController::last_busy_time($value->admin_id,$cc_id);
        }
        return response()->json($data);
    }

    public static function  crm_status_time($staff_id,$cc_id){
         $data = CC_Crm_Activity::select(DB::raw('TIMEDIFF(NOW(), start_datetime) AS start_time'))
            ->where('staff_id',$staff_id)
            ->where('cc_id',$cc_id)
            ->whereRaw('DATE(update_datetime) = DATE(NOW())')
            ->orderBy('id', 'desc')
            ->first();
        return isset($data->start_time) ? $data->start_time : "-";
    }

    public static function last_busy_time($staff_id,$cc_id){
        $duration = DB::select("
                SELECT TIMEDIFF(
                    (SELECT TIME(update_datetime) FROM cc_queue_stats
                    WHERE staff_id = ? AND cc_id = ? AND DATE(update_datetime) = DATE(NOW()) AND STATUS = '0'
                    ORDER BY id DESC LIMIT 1),
                    (SELECT TIME(update_datetime) FROM cc_queue_stats
                    WHERE staff_id = ? AND cc_id = ? AND DATE(update_datetime) = DATE(NOW()) AND STATUS = '-1'
                    ORDER BY id DESC LIMIT 1)
                ) AS duration
        ", [$staff_id, $staff_id, $cc_id, $cc_id]);
        return isset($duration->duration) ? $duration->duration : "-";
    }

    public function queue_stats(){
        $cc_id = Session::get('cc_id');
        $data = CC_Queue_Stats::select(
            'cc_queue_stats.*',
            DB::raw('TIME(cc_queue_stats.enqueue_datetime) as q_start_time'),
            DB::raw('TIMEDIFF(NOW(), cc_queue_stats.enqueue_datetime) AS duration'),
            'cc_admin.full_name as agent_name'
        )->leftJoin('cc_admin', 'cc_admin.admin_id', '=', 'cc_queue_stats.staff_id')
        ->where('cc_queue_stats.cc_id',$cc_id)
        ->where('cc_queue_stats.STATUS', '<>', '0')
        ->whereColumn('cc_queue_stats.enqueue_datetime', 'cc_queue_stats.dequeue_datetime')
        ->whereIn('cc_queue_stats.STATUS', [1, 2])
        ->whereNotIn('cc_queue_stats.call_status', ['IVR', 'OFFTIME'])
        ->get();
        foreach($data as $value){
            $value->call_datetime = date('H:m:s', strtotime($value->call_datetime));
            if($value->status == 1){
                $value->status = "Waiting";
            }elseif($value->status == 2){
                $value->status = "Ringing";
            }
        }
        return response()->json($data);
    }

    public function received_call_stats(){
        $cc_id = Session::get('cc_id');
        $data = CC_Queue_Stats::select([
                'cc_queue_stats.*',
                DB::raw("TIMEDIFF(dequeue_datetime, enqueue_datetime) AS duration"),
                DB::raw("Date(update_datetime) as date"),
                DB::raw("Time(update_datetime) as time"),
                'admin.full_name as agent_name',

            ])
            ->leftJoin('cc_admin as admin', 'admin.admin_id', '=', 'cc_queue_stats.staff_id')
            ->where('admin.cc_id',$cc_id)
            ->whereRaw("TIMEDIFF(staff_end_datetime, staff_start_datetime) <> '00:00:00'")
            ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->where('cc_queue_stats.call_type', 'INBOUND')
            ->orderByDesc('id')
            ->get();
        foreach($data as $key => $value){
            $value->time = Carbon::parse($value->time)->format('h:i:s A');
        }
        return response()->json($data);
    }

    public function dropped_call_stats(Request $request){
        // Start building the query
        $cc_id = Session('cc_id');
        $data = DB::table('cc_queue_stats')
        ->select(
            '*',
            DB::raw('TIME(update_datetime) as time'),
            DB::raw('DATE(update_datetime) as date'),
            DB::raw('TIMEDIFF(update_datetime, enqueue_datetime) AS duration')
        )
        ->where('cc_id',$cc_id)
        ->where('status', 0)
        ->where('call_status','!=','ANSWER')
        ->where('call_type', 'INBOUND')
        ->whereRaw("DATE(update_datetime) = DATE(NOW())")
        ->orderBy('id', 'desc')
        ->get();

        foreach($data as $value){
            $value->time = Carbon::parse($value->time)->format('h:i:s A');
        }

        // Execute the query and get the results
        return response()->json($data);
    }

    public function off_time_stats(Request $request){
        $cc_id = Session::get('cc_id');
        $data = CC_Queue_Stats::where('cc_id',$cc_id)->paginate(10);
        $table = "Off-Time-Stats";
        return view('callAgentStats',compact('data','table'));
    }

    public function wallboard(Request $request){
        $cc_id = Session::get('cc_id');
        $TotalCalls = CC_Queue_Stats::where('cc_id',$cc_id)
            //->where('call_type', '<>', '')
	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())") 
            ->count('unique_id');
        $TransferCalls = CC_Queue_Stats::where('STATUS', 0)
            ->where('cc_id',$cc_id)
            ->where('call_type', 'INBOUND')
	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->where('call_status', 'TRANSFER')
            ->count('unique_id');
        $ShiftCalls = CC_Queue_Stats::where('STATUS', 0)
            ->where('cc_id',$cc_id)
            ->where('call_type', 'INBOUND')
	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
            ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->where('call_status', 'SHIFT')
            ->count('unique_id');
        $InboundCallsAnswer = CC_Queue_Stats::whereIn('STATUS',[0,1])
            ->where('cc_id',$cc_id)
            ->where('call_type', 'INBOUND')
	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->where('call_status', 'ANSWERED')
            ->count('unique_id');
         $DropCalls = CC_Queue_Stats::where('STATUS', 0)
            ->where('cc_id',$cc_id)
            ->where('call_type', 'INBOUND')
	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
	    ->where('call_status', '!=' ,'ANSWERED')
    	    ->where('staff_id','!=','')
            ->count('unique_id');
        $CampaignCalls = CC_Queue_Stats::where('STATUS', 0)
            ->where('cc_id',$cc_id)
            ->where('call_type', 'CAMPAIGN')
    	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->where('call_status', 'ANSWERED')
            ->count('unique_id');
        $InboundCalls = CC_Queue_Stats::whereIn('STATUS', [0,1])
            ->where('cc_id',$cc_id)
            ->where('call_type', 'INBOUND')
            //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
	    //->whereIn('call_status', ['ANSWERED', 'DROP', 'TRANSFER', 'SHIFT'])
	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->count('unique_id');
        $OutboundCalls = CC_Queue_Stats::where('STATUS', 0)
            ->where('cc_id',$cc_id)
            ->where('call_type', 'OUTBOUND')
	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->distinct('unique_id')
            ->count();
        $AgentCalls = CC_Admin::where('is_busy', 1)->where('cc_id',$cc_id)->count();
        $OffTimeCalls = CC_Queue_Stats::where('STATUS', 0)
            ->where('cc_id',$cc_id)
            ->where('call_type', 'INBOUND')
    	    //->whereBetween('update_datetime', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
    	    ->whereRaw("DATE(update_datetime) = DATE(NOW())")
            ->where('call_status', 'OFFTIME')
            ->distinct('unique_id')
            ->count();
        $data = array(
            "TotalCalls"            => $TotalCalls,
            "TransferCalls"         => $TransferCalls,
            "ShiftCalls"            => $ShiftCalls,
            "InboundCallsAnswer"    => $InboundCallsAnswer,
            "DropCalls"             => $DropCalls,
            "CampaignCalls"         => $CampaignCalls,
            "InboundCalls"          => $InboundCalls,
            "OutboundCalls"         => $OutboundCalls,
            "AgentCalls"            => $AgentCalls,
            "OffTimeCalls"          => $OffTimeCalls
        );
        return view('callCenterWallboard',compact('data'));
    }

    public function agent_statistics_summary(Request $request){
        $data = array();
        $cc_id = Session::get('cc_id');
        $cc_admin = CC_Admin::where('designation','Agents')->where('cc_id',$cc_id)->orderByDesc('updated_at')->paginate(10);
        foreach($cc_admin as $value){
            $InboundCalls = CC_Queue_Stats::where('staff_id',$value->admin_id)
                ->where('cc_id',$cc_id)
                ->where('call_type','INBOUND')
                ->whereRaw("TIMEDIFF(staff_end_datetime, staff_start_datetime) > '00:00:00'")
                ->whereRaw("DATE(update_datetime) = DATE(NOW())")
                ->distinct('unique_id')
                ->count();
            $OutboundCalls = CC_Queue_Stats::where('staff_id',$value->admin_id)
                ->where('cc_id',$cc_id)
                ->where('call_type','OUTBOUND')
                ->whereRaw("TIMEDIFF(staff_end_datetime, staff_start_datetime) <> '00:00:00'")
                ->whereRaw("DATE(update_datetime) = DATE(NOW())")
                ->distinct('unique_id')
                ->count();
            $BreakTime = CC_Crm_Activity::where('staff_id',$value->admin_id)
                ->where('cc_id',$cc_id)
                ->whereRaw("TIMEDIFF(end_datetime, start_datetime) <> '00:00:00'")
                ->whereNotIn('STATUS', [6, 1])
                ->whereDate('update_datetime', date('Y-m-d'))
                ->groupBy('staff_id')
                ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_datetime, start_datetime)))) AS AGENT_BREAK_TIME')
                ->first();
            $AssignmentTime = CC_Crm_Activity::where('staff_id',$value->admin_id)
                ->where('cc_id',$cc_id)
                ->whereRaw("TIMEDIFF(end_datetime, start_datetime) <> '00:00:00'")
                ->where('STATUS',6)
                ->whereDate('update_datetime', date('Y-m-d'))
                ->groupBy('staff_id')
                ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_datetime, start_datetime)))) AS AGENT_ASSIGNMENT_TIME')
                ->first();
            $LoginTime = CC_Login_Activity::where('staff_id', $value->admin_id)
                ->where('cc_id',$cc_id)
                ->whereDate('login_datetime', date('Y-m-d'))
                ->whereDate('logout_datetime', date('Y-m-d'))
                ->selectRaw('COUNT(*) AS trec, TIME(MIN(login_datetime)) AS login_time,
                    CASE TIME(MAX(login_datetime)) WHEN TIME(MAX(logout_datetime)) THEN TIME(NOW())
                    ELSE TIME(MAX(logout_datetime))
                    END AS logout_time,
                    CASE TIME(MAX(login_datetime)) WHEN TIME(MAX(logout_datetime)) THEN TIMEDIFF(NOW(), MIN(login_datetime))
                    ELSE TIMEDIFF(MAX(logout_datetime), MIN(login_datetime))
                    END AS duration')
                ->first();
            $BusyTime = CC_Queue_Stats::select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(TIME(cc_queue_stats.update_datetime), TIME(l.update_datetime))))) as AGENT_BUSY_TIME'))
                ->join('cc_queue_stats_logs as l', function ($join) {
                    $join->on('cc_queue_stats.unique_id', '=', 'l.unique_id')
                        ->on('cc_queue_stats.staff_id', '=', 'l.staff_id')
                        ->where('cc_queue_stats.status', '=', '0')
                        ->where('l.status', '=', '-1');
                })
                ->where('cc_id',$cc_id)
                ->where('cc_queue_stats.staff_id',$value->admin_id)
                ->whereDate('cc_queue_stats.update_datetime', '=', date('Y-m-d'))
                ->first();
            $array = array(
                "Admin_FullName"    => $value->full_name,
                'InboundCalls'      => $InboundCalls,
                'OutboundCalls'     => $OutboundCalls,
                'BreakTime'         => empty($BreakTime->AGENT_BREAK_TIME) ? "-" : $BreakTime->AGENT_BREAK_TIME,
                'AssignmentTime'    => empty($AssignmentTime->AGENT_ASSIGNMENT_TIME) ? "-" : $AssignmentTime->AGENT_ASSIGNMENT_TIME,
                'LoginTime'         => empty($LoginTime->login_time) ? "-" : $LoginTime->login_time,
                'BusyTime'          => empty($BusyTime->AGENT_BUSY_TIME) ? "-" : $BusyTime->AGENT_BUSY_TIME,
                'TimeDuration'      => empty($LoginTime->logout_time) ? "-" : $LoginTime->logout_time,
            );
            array_push($data,$array);
        }
        return view('callAgentStatisticsSummary',compact('data','cc_admin'));
    }


}
