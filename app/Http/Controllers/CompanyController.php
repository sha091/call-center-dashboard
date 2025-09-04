<?php

namespace App\Http\Controllers;

use App\Models\CC_Admin;
use Illuminate\Http\Request;
use App\Models\CC_Company_Info;
use App\Models\CC_Working_Hours;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function home(Request $request){
        $cdrs = CC_Company_Info::paginate(10);
        return view('SuperAdmin.viewCompanies',compact('cdrs'));
    }

    public function createCompany(Request $request){
        return view('SuperAdmin.createCompany');
    }

    public function storeCompny(Request $request){
        $validate = Validator::make($request->all(), [
            'company_name'      => 'required',
            'master_number'     => 'required',
            'poc_name'          => 'required',
            'agents'            => 'required',
            'address'           => 'required',
            'email'             => 'required'
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $file = $request->file('agents');
        if (($handle = fopen($file, 'r')) !== FALSE) {
            fgetcsv($handle);
            $company = CC_Company_Info::where('master_number',$request->master_number)->first();
            if($company){
                Toastr::error("The company already exists for the given master number !", 'Error', ["positionClass" => "toast-top-center"]);
                return redirect()->back();
            }
            $company = CC_Company_Info::create([
                "company_name"      => $request->company_name,
                "master_number"     => $request->master_number,
                "poc_name"          => $request->poc_name,
                "address"           => $request->address,
                'cc_id'             => uniqid(),
                'status'            => 1
            ]);
            $agent_exten = CC_Admin::orderBy('admin_id','DESC')->first();
            $agent_exten = $agent_exten->agent_exten+1;
            CC_Admin::create([
                'agent_exten'   => $agent_exten,
                'full_name'     => "Admin",
                'email'         => $request->email,
                'password'      => Hash::make('Admin@123'),
                'org_password'  => "Admin@123",
                'status'        => 1,
                'designation'   => "Supervisor",
                'department'    => 'General Inquiry',
                'cc_id'         => $company->cc_id
            ]);
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if(isset($data[0]) && isset($data[1])){
                    $check_email_exists = CC_Admin::where('email',$data[1])->first();
                    if($check_email_exists){
                        Toastr::error("This email address ".$data[1]." already exists.", 'Error', ["positionClass" => "toast-top-center"]);
                        CC_Admin::where('cc_id',$company->cc_id)->delete();
                        CC_Company_Info::where('master_number',$request->master_number)->delete();
                        return redirect()->back();
                    }
                    $agent_exten = CC_Admin::orderBy('admin_id','DESC')->first();
                    $agent_exten = $agent_exten->agent_exten+1;
                    CC_Admin::create([
                        'agent_exten'   	=> $agent_exten,
                        'full_name'     	=> $data[0],
                        'email'         	=> $data[1],
                        'password'      	=> Hash::make('Admin@123'),
                        'org_password'  	=> "Admin@123",
                        'status'        	=> 1,
                        'designation'   	=> "Agents",
                        'department'    	=> 'General Inquiry',
                        'cc_id'         	=> $company->cc_id,
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
                }else{
                    CC_Company_Info::where('master_number',$request->master_number)->delete();
                    CC_Admin::where('cc_id',$company->cc_id)->delete();
                    Toastr::error("The CSV file format is incorrect. Please download the sample file.", 'Error', ["positionClass" => "toast-top-center"]);
                    return redirect()->back();
                }
            }
            fclose($handle);
            $week = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
            foreach($week as $key => $day){
                CC_Working_Hours::create([
                    'cc_id'             => $company->cc_id,
                    'today'             => $day,
                    'start_time'        => "00:00",
                    'end_time'          => "23:59",
                    'option_off_time'   => "ivr"
                ]);
            }
	        Toastr::success('Company Created Successfully....', 'success', ["positionClass" => "toast-top-right"]);
            exec('sudo asterisk -rx "sip reload"');
            return redirect('SuperAdmin/company');
        }
    }

    public function changeStatus(Request $request){
       $company = CC_Company_Info::where('id',$request->company_id)->first();
        if($company){
            if($request->status == "active"){
                $status = 1;
            }else{
                $status = 0;
            }
            $company->status = $status;
            $company->save();
            return true;
        }
        return false;
    }

    public function changeAutoDetectionStatus(Request $request){
        $company = CC_Company_Info::where('id',$request->company_id)->first();
         if($company){
             if($request->status == "active"){
                 $status = 1;
             }else{
                 $status = 0;
             }
             $company->auto_detection = $status;
             $company->save();
             return true;
         }
         return false;
    }

    public function search(Request $request){
        $search = $request->input('search');
        // Filter the data based on the search query
        $cdrs = CC_Company_Info::when($search, function ($query, $search) {
                        return $query->where('company_name', 'like', '%' . $search . '%')
                                    ->orWhere('poc_name', 'like', '%' . $search . '%')
                                    ->orWhere('cc_id', 'like', '%' . $search . '%')
                                    ->orWhere('master_number', 'like', '%' . $search . '%');
                    })
                    ->paginate(10);
        // Return the filtered data
        $tableRows = view('SuperAdmin.partial.user-view-table', compact('cdrs'))->render(); // Partial view for table rows
        $paginationLinks = view('vendor.pagination.bootstrap-5', ['paginator' => $cdrs])->render();

        return response()->json([
            'tableRows' => $tableRows,
            'paginationLinks' => $paginationLinks,
        ]);
    }

    public function getDownload(Request $request){
        $file="/var/www/html/dev_cc/storage/app/public/sampleFile.csv";
        return Response::download($file);
    }
}
