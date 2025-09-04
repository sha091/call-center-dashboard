<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Roles;
use App\Models\CC_Admin;
use App\Models\Misscall;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CC_Queue_Stats;
use App\Models\CC_Crm_Activity;
use Yajra\DataTables\DataTables;
use App\Models\CC_Login_Activity;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;


class UserController extends Controller
{

    public function index(){
        if (Auth::check()) {
            if(Auth::user()->designation == "Supervisor"){
                return redirect()->intended('dashboard');
            }elseif(Auth::user()->designation == "SuperAdmin" ){
                return redirect()->intended('SuperAdmin/dashboard');
            }
            return redirect()->intended('Agent/dashboard');
        }
        return view('auth.login');
    }

    public function postLogin(Request $request){
        $validate = Validator::make($request->all(), [
            'email'         => ['required','email'],
            'password'      => ['required',
                                'string',
                                'min:7',              // must be at least 10 characters in length
                                'regex:/[a-z]/',      // must contain at least one lowercase letter
                                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                'regex:/[0-9]/',      // must contain at least one digit
                                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);

        if($validate->fails()){
            Toastr::error('Email and Password Missing', 'Error', ["positionClass" => "toast-top-center"]);
            return redirect("login");
        }
        
        $user = CC_Admin::with('company')
                        ->where('email',$request->email)                        
                        ->where('status',1)
                        ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            Toastr::error('Email and Password Incorrect..!', 'Error', ["positionClass" => "toast-top-center"]);
            return redirect("login");
        }                                 
        if($user){            
            Auth::login($user);            
            if (Auth::check()) {                
                $user->is_crm_login = 1;
                $user->save();
                Toastr::success('Login Sucessfully..!', 'success', ["positionClass" => "toast-top-right"]);
                Session::put('admin_id', $user->admin_id);
                Session::put('AgentExten', $user->agent_exten);
                Session::put('cc_id',$user->cc_id);                
                CC_Login_Activity::updateOrCreate(
                        ['staff_id' => $user->admin_id],
                        [
                            'staff_id'          => $user->admin_id,
                            'login_datetime'    => now(),
                            'update_datetime'   => now(),
                            'status'            => 2,
                            'cc_id'             => $user->cc_id
                        ]);
                if($user->designation == "Supervisor"){
                    return redirect()->intended('dashboard');
                }elseif($user->designation == "SuperAdmin" ){
                    return redirect()->intended('SuperAdmin/dashboard');
                }
                return redirect()->intended('Agent/dashboard');
            }
        }
        Toastr::error('Email and Password Incorrect..!', 'Error', ["positionClass" => "toast-top-center"]);
        return redirect("login");

    }

    public function registration(){
        $roles =  Roles::all();
        return view('auth.user_create',compact('roles'));
    }

    public function user_registration(Request $request){
        
        $validate = Validator::make($request->all(), [
            'agent_exten'   => ['required','integer'],
            'full_name'     => ['required','string','unique:cc_admin'],
            'email'         => ['required','email','unique:cc_admin'],
            'password'      => ['required',
                                'string',
                                'min:7',             // must be at least 10 characters in length
                                'regex:/[a-z]/',      // must contain at least one lowercase letter
                                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                'regex:/[0-9]/',      // must contain at least one digit
                                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect("user");
            //return back()->withErrors($validate->errors())->withInput();
        }
        $checkExtension = CC_Admin::where('agent_exten',$request->agent_exten)->first();
        
        if($checkExtension){
            Toastr::error('Extension already exists...', 'Error', ["positionClass" => "toast-top-center"]);
            return redirect("user");
        }
        $agent_exten = $request->agent_exten;
        CC_Admin::create([
            'agent_exten'       => $request->agent_exten,
            'full_name'         => $request->full_name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'org_password'      => $request->password,
            'status'            => 1,
            'designation'       => "Agents",
            'department'        => 'General Inquiry',
            'cc_id'             => Session::get('cc_id'),
            'is_phone_login'	=> 1
        ]);
        $path  =  "/var/www/html/dev_cc/asterisk_conf/custom_sip.conf";
        $check_content_exists = trim(`cat $path | grep "\[$agent_exten\]"|wc -l`);
        if($check_content_exists <= 0){
            $sipHandle    = fopen($path,"a");
            $write  = "[$agent_exten]\n";
            $write  .= "username = $agent_exten\n";
            $write  .= "type = friend\nhost = dynamic\nsecret = 123\ncontext = call_center\n";
            $write  .= "callerid = $agent_exten\nmailbox = $agent_exten@call_center\ncall-limit = 1\n\n";
            fwrite($sipHandle,$write);
            fclose($sipHandle);
        }
        Toastr::success('User Created Sucessfully..!', 'success', ["positionClass" => "toast-top-right"]);
        exec('sudo asterisk -rx "sip reload"');
        return redirect('user');

    }

    public function view_dashboard(Request $request){        
        if(Auth::check()){         
            if(!Session::get('admin_id')){return redirect("logout");}
            $cc_id = Session::get('cc_id');
            if(Auth::user()->designation !== "Supervisor"){
                $totalCalls             = CC_Queue_Stats::where('staff_id',Auth::user()->admin_id)
                                            ->where('cc_id',$cc_id)
                                            ->count();
                $todayCalls             = CC_Queue_Stats::where('staff_id',Auth::user()->admin_id)
                                            ->where('cc_id',$cc_id)
                                            ->whereDate('call_datetime', Carbon::today())
                                            ->count();
                $todayMissedCalls       = CC_Queue_Stats::where('staff_id',Auth::user()->admin_id)
                                            ->where('cc_id',$cc_id)
                                            ->whereDate('call_datetime', Carbon::today())
                                            ->where('call_status','MISSED CALL')
                                            ->count();
                $todayAnswerdCalls      = CC_Queue_Stats::where('staff_id',Auth::user()->admin_id)
                                            ->where('cc_id',$cc_id)
                                            ->whereDate('call_datetime', Carbon::today())
                                            ->where('call_status','ANSWERED')
                                            ->count();
                $datesWithTimes = [];
                $datesWithTimesCout = [];
                $today = Carbon::now();
                for ($i = 0; $i < 7; $i++) {
                    $datesWithTimes[] = $today->copy()->subDays($i)->format('Y-m-d');
                    $datesWithTimesCount[] = CC_Queue_Stats::where('staff_id',Auth::user()->admin_id)
                                            ->where('cc_id',$cc_id)
                                            ->whereDate('call_datetime', $today->copy()->subDays($i)->format('Y-m-d'))
                                            ->count();
                }
                $dashboard  = array(
                    'TotalCalls'            => $totalCalls == 0 ? 0  : $totalCalls,
                    'TodayCalls'            => $todayCalls  == 0 ? 0 : $todayCalls,
                    'TodayMissedCalls'      => $todayMissedCalls == 0 ? 0 : $todayMissedCalls,
                    'TodayAnswerdCalls'     => $todayAnswerdCalls == 0 ? 0 : $todayAnswerdCalls,
                    'graphDates'            => $datesWithTimes,
                    'graphCounts'           => $datesWithTimesCount
                );
                return view('dashboard',compact('dashboard'));
            }
            $totalLogins        = CC_Admin::where('is_crm_login', 1)
                                    ->where('cc_id',$cc_id)
                                    ->where('is_phone_login', 1)
                                    ->where('STATUS', 1)
                                    ->count();
            $liveCalls          = CC_Admin::where('is_busy', 1)->where('cc_id',$cc_id)->count();
            $todayCalls         = CC_Queue_Stats::where('cc_id',$cc_id)->whereDate('call_datetime', Carbon::today())->count();
            $todayAnswerdCalls  = CC_Queue_Stats::where('cc_id',$cc_id)
                                    ->whereDate('call_datetime', Carbon::today())
                                    ->where('call_status','ANSWERED')
                                    ->count();
            $datesWithTimes = [];
            $datesWithTimesCout = [];
            $today = Carbon::now();
            for ($i = 0; $i < 7; $i++) {
                $datesWithTimes[] = $today->copy()->subDays($i)->format('Y-m-d');
                $datesWithTimesCount[] = CC_Queue_Stats::where('cc_id',$cc_id)->whereDate('call_datetime', $today->copy()->subDays($i)->format('Y-m-d'))->count();
            }
            $dashboard = array(
                "TotalLogins"           => $totalLogins == 0 ? 0 : $totalLogins,
                "LiveCalls"             => $liveCalls == 0 ? 0 : $liveCalls,
                "TodayCalls"            => $todayCalls == 0 ? 0 : $todayCalls,
                "TodayAnswerdCalls"     => $todayAnswerdCalls == 0 ? 0 : $todayAnswerdCalls,
                'graphDates'            => $datesWithTimes,
                'graphCounts'           => $datesWithTimesCount
            );
            return view('dashboard',compact('dashboard'));
        }
        Toastr::error('You are not allowed to access..!', 'Error', ["positionClass" => "toast-top-center"]);
        return redirect("login");
    }

    public function userList(){
        //$user_data = User::with('Role')->where('user_status',1)->orderBy('created_at','DESC')->paginate(10);
        $cc_id = Session::get('cc_id');
        $agent_exten = CC_Admin::orderBy('admin_id','DESC')->first();
        $agent_exten = $agent_exten->agent_exten+1;
        $user_data = CC_Admin::where('cc_id',$cc_id)->orderBy('admin_id','DESC')->paginate(10);
        return view('auth.user_list',compact('user_data','agent_exten'));
    }

    public function logOut() {
        $admin_id   = Session::get('admin_id');
        $cc_id      = Session::get('cc_id');
        CC_Crm_Activity::where('staff_id', $admin_id )
        ->where('cc_id',$cc_id)
        ->whereDate('update_datetime', now()->toDateString())  // equivalent to DATE(NOW())
        ->orderByDesc('id')
        ->limit(1)
        ->update(['end_datetime' => now()]);

        CC_Login_Activity::where('staff_id', $admin_id)
        ->where('cc_id',$cc_id)
        ->where('status', 2)
        ->update([
            'logout_datetime' => now(),
            'update_datetime' => now(),
            'status' => 1
        ]);

        CC_Queue_Stats::where('staff_id', $admin_id)
        ->where('cc_id',$cc_id)
        ->where('status', -1)
        ->update(['status' => 0, 'update_datetime' => now()]);

        $user = CC_Admin::where('admin_id',$admin_id)->where('cc_id',$cc_id)->first();
        if($user){
            $user->is_crm_login = 0;
            $user->is_busy = 0;
            $user->save();
        }
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function call_center_status(){
        $cc_id = Session::get('cc_id');
        $call_center_status = DB::table('cc_schadule_config')->where('cc_id',$cc_id)->limit(1)->first();        
        return $call_center_status;
    }

    public function updateAdminAndGetQueueStats(){
        $agent_id = Session::get('admin_id');
        $cc_id = Session::get('cc_id');
        $phone_login_status = CC_Admin::where('admin_id',$agent_id)->where('cc_id',$cc_id)->first();
        if(!$phone_login_status->is_phone_login){
            return response()->json(array('status' => '5') );
        }
        // Update query for the admin table
        CC_Admin::when($agent_id, function ($query) use ($agent_id,$cc_id) {
                return $query->where('admin_id', $agent_id)->where('cc_id',$cc_id);
        })->update(['staff_updated_date' => now()]);

        $query = CC_Queue_Stats::select(
                'id',
                'unique_id',
                'caller_id',
                'staff_id',
                'status',
                'update_datetime',
                DB::raw('MINUTE(TIMEDIFF(NOW(), staff_start_datetime)) AS minutes'),
                DB::raw('SECOND(TIMEDIFF(NOW(), staff_start_datetime)) AS seconds')
            )->when($agent_id, function ($query) use ($agent_id,$cc_id) {
                return $query->where('staff_id', $agent_id)
                             ->where('cc_id',$cc_id)
                             ->where('status', '!=', 0);
            })->orderByDesc('id')
            ->limit(1)
            ->first();
        if($query){
	     Session::put('unique_id', $query->unique_id);
	     if($query->status == 1){
            	if (!empty(Session::get('caller_id'))) {
                	session()->forget('caller_id');
            	}
             }else{
            	Session::put('caller_id',$query->caller_id);
             }
        }
        return response()->json($query);
    }

    public function updateBreakTime(Request $request){
        $validate = Validator::make($request->all(), [
            'selectedValue' =>  'required',
            'admin_id'      => 'required'
        ]);

        if($validate->fails()){
            return false;
        }

        $cc_id = Session::get('cc_id');

        if($request->selectedValue == "Online"){
            $status = 1;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Namaz Break"){
            $status = 2;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Lunch Break"){
            $status = 3;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Tea Break"){
            $status = 4;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Auxiliary Time"){
            $status = 5;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Assignment"){
            $status = 6;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Campaign"){
            $status = 7;
            Session::put('TimeStatus',$request->selectedValue);
        }elseif($request->selectedValue == "Offline"){
            $status = 0;
            Session::put('TimeStatus',$request->selectedValue);
        }else{
            $status = 0;
            Session::put('TimeStatus',$request->selectedValue);
        }

        $activity = CC_Crm_Activity::where('staff_id', $request->admin_id)
            ->where('cc_id',$cc_id)
            ->whereDate('update_datetime', Carbon::today())  // Only today's date
            ->orderByDesc('id')
            ->first();  // Get the most recent entry

        if ($activity) {
            $activity->update([
                'end_datetime' => Carbon::now(),  // Set the current time as end_datetime
            ]);
        }

        // Step 2: Update the admin table
        CC_Admin::where('admin_id', $request->admin_id)->where('cc_id',$cc_id)
            ->update([
                'is_crm_login' => $status,
            ]);

        // Step 3: Insert a new record into crm_activity
        DB::table('cc_crm_activity')
            ->insert([
                'staff_id' => $request->admin_id,
                'start_datetime' => Carbon::now(),
                'end_datetime' => Carbon::now(),
                'status' => $status,
                'update_datetime' => Carbon::now(),
                'cc_id' => $cc_id
            ]);

        return true;

    }

    public function user_status_update(Request $request){
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
        $user = CC_Admin::where('admin_id',$request->admin_id)->where('cc_id',$cc_id)->first();
        if($user){
            if($request->status == "active"){
                $status = 1;
            }else{
                $status = 0;
            }
            $user->status = $status;
            $user->save();
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

    public function change_password(Request $request){
        $validate = Validator::make($request->all(), [
            'password'      => ['required',
                                        'string',
                                        'min:7',             // must be at least 10 characters in length
                                        'regex:/[a-z]/',      // must contain at least one lowercase letter
                                        'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                        'regex:/[0-9]/',      // must contain at least one digit
                                        'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'admin_id'      => ['required']
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect("user");
        }
        $cc_id = Session::get('cc_id');
        $user = CC_Admin::where('admin_id',$request->admin_id)->where('cc_id',$cc_id)->first();
        if($user){
            $user->password = md5($request->password);
            $user->org_password = $request->password;
            $user->save();
            Toastr::success('Password Reset Complete', 'success', ["positionClass" => "toast-top-right"]);
            return redirect('user');

        }
        Toastr::error('User Not Found', 'Error', ["positionClass" => "toast-top-center"]);
        return redirect("user");

    }

    public function super_admin_dashboard(Request $request){
        if(Auth::check()){
            $startDate  = isset($request->startDate)?$request->startDate:false;
            $endDate    = isset($request->endDate)?$request->endDate:false;
            $cc_id      = isset($request->company_id)?$request->company_id:false;
            $totalCompanies         = DB::table('cc_company_info')
                                            ->where(function($query) use($cc_id){
                                                if($cc_id){
                                                    $query->where('cc_id', $cc_id);
                                                }
                                            })->count();
            $totalActiveCompanies   = DB::table('cc_company_info')->where('status',1)
                                            ->where(function($query) use($cc_id){
                                                if($cc_id){
                                                    $query->where('cc_id', $cc_id);
                                                }
                                            })->count();
            $totalAgents            = CC_Admin::where('designation','Agents')
                                            ->where(function($query) use($cc_id){
                                                if($cc_id){
                                                    $query->where('cc_id',$cc_id);
                                                }
                                            })->count();
            $totalActiveAgents      = CC_Admin::where('designation','Agents')->where('status',1)
                                            ->where(function($query) use($cc_id){
                                                if($cc_id){
                                                    $query->where('cc_id',$cc_id);
                                                }
                                            })->count();
            $companies              = DB::table('cc_company_info')->get();
            $datesWithTimes = [];
            $inboundCout = [];
            $outboundCout = [];
            $today = Carbon::now();
            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate);
                $endDate = Carbon::parse($endDate);
            } else {
                $startDate = $today->copy()->subDays(6); // Default to 7 days ago from today.
                $endDate = $today; // Default to today.
            }
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $formattedDate = $date->format('Y-m-d');
                $datesWithTimes[] = $formattedDate;

                $inboundCout[] = CC_Queue_Stats::where('call_type', 'INBOUND')
                    ->where(function($query) use($cc_id){
                        if($cc_id){
                            $query->where('cc_id',$cc_id);
                        }
                    })
                    ->whereDate('call_datetime', $formattedDate)
                    ->count();

                $outboundCout[] = CC_Queue_Stats::where('call_type', 'OUTBOUND')
                    ->where(function($query) use($cc_id){
                        if($cc_id){
                            $query->where('cc_id',$cc_id);
                        }
                    })
                    ->whereDate('call_datetime', $formattedDate)
                    ->count();

                $totalCount[] = CC_Queue_Stats::whereDate('call_datetime', $formattedDate)
                    ->where(function($query) use($cc_id){
                        if($cc_id){
                            $query->where('cc_id',$cc_id);
                        }
                    })->count();
            }
            $dashboard  = array(
                'TotalCompanies'        => $totalCompanies == 0 ? 0  : $totalCompanies,
                'TotalActiveCompanies'  => $totalActiveCompanies  == 0 ? 0 : $totalActiveCompanies,
                'TotalAgents'           => $totalAgents == 0 ? 0 : $totalAgents,
                'TotalActiveAgents'     => $totalActiveAgents == 0 ? 0 : $totalActiveAgents,
                'graphDates'            => $datesWithTimes,
                'inboundCounts'         => $inboundCout,
                'outboundCounts'        => $outboundCout,
                'totalCount'            => $totalCount
            );
            return view('SuperAdmin.dashboard',compact('dashboard','companies'));
        }
        Toastr::error('You are not allowed to access..!', 'Error', ["positionClass" => "toast-top-center"]);
        return redirect("login");
    }
}
