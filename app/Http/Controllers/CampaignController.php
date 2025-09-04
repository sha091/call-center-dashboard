<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CC_Bulk_Obd;
use App\Models\CC_Campaign;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CC_Company_Info;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{

    public function home(Request $request){        
        $cc_id = Session::get('cc_id');
        $get_campaign   = CC_Campaign::where('cc_id',$cc_id)->where('delete_status',0)->orderBy('created_at','DESC')->paginate(10);        
        foreach($get_campaign as $value){            
            $campaign_id = $value->campaign_id;
            $value->call_limit = CC_Bulk_Obd::where('campaign_id',$campaign_id)->count();
            if($value->status == 2){
                $value->runningStatus = "Completed";
                if($value->end_time_status == 0){
                    DB::update("update cc_campaign set end_time_status = 1 , camp_end_time = '$value->updated_at' where id = $value->id");
                }
            }elseif($value->status == 1){
                $value->camp_end_time = "-";
                $value->runningStatus = "Running";
            }
            else{
                $value->camp_end_time = "-";
                $value->runningStatus = "Pause";                
            }
        }       
        return view('campaign',compact('get_campaign'));
    }

    public function CreateCampaign(Request $request){
        $validator = Validator::make($request->all(),[
            'campaign_name'             => ['required','string'],
            'csv_file'                  => 'required',            
        ]);
        if($validator->fails()){
            Toastr::error($validator->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $cc_id = Session::get('cc_id');
        $file = fopen($request->csv_file,'r');
        $campaign_id = Str::random(5);
        $check_name = CC_Campaign::where('campaign_name',$request->campaign_name)->first();
        if($check_name){
            Toastr::error("Campaign Name Already Exists.", 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $master_number = CC_Company_Info::where('cc_id',$cc_id)->first();
        $master_number = $master_number->master_number;
        if(!empty($request->start_time) && !empty($request->end_time)){
            $start_date = $request->start_date."".$request->start_time;
            $end_date   = $request->end_date."".$request->end_time;
            $start_date =  date('Y-m-d G:i:s',strtotime($start_date));
            $end_date   =  date('Y-m-d G:i:s',strtotime($end_date));
            $campaign   = CC_Campaign::create([
                "cc_id"             => $cc_id,
                "campaign_id"       => $campaign_id,
                "campaign_name"     => $request->campaign_name,
                "call_type"         => "schedule",
                "camp_start_time"   => $start_date,
                "camp_end_time"     => $end_date,
                "master_number"     => $master_number,
            ]);
        }else{
            $campaign   = CC_Campaign::create([
                "cc_id"             => $cc_id,
                "campaign_id"       => $campaign_id,
                "campaign_name"     => $request->campaign_name,
                "call_type"         => "now",
                "camp_start_time"   => Carbon::now(),
                "camp_end_time"     => Carbon::now(),
                "master_number"     => $master_number,
            ]);
        }
        while (($filedata = fgetcsv($file,1048576,",")) !== FALSE){
            $num = count($filedata);
            if($num == 2 ){$num = 1;}
            for ($c=0; $c < $num; $c++){
                if(!preg_match('/^[0-9]*$/',$filedata[$c])) {
                    CC_Campaign::where('campaign_id',$campaign_id)->delete();
                    CC_Bulk_Obd::where('campaign_id',$campaign_id)->delete();
                    Toastr::error("non integers are not allowed and msisdn must be 10 characters", 'Error', ["positionClass" => "toast-top-center"]);
                    return redirect()->back();
                }else{
                    if (!empty($filedata[$c])){
                        $number = '0'.$filedata[$c];
                        CC_Bulk_Obd::create([
                            "campaign_id"   => $campaign_id,
                            "caller_id"     => $number,
                            "cc_id"         => $cc_id,
                        ]);
                    }
                }

            }
        }
        Toastr::success('Campaign Created Successfully....', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function getDownload(Request $request){
        $file="/var/www/html/dev_cc/storage/app/public/obdSampleFile.csv";
        return \Response::download($file);
    }

    public function updateCampaignStatus(Request $request){
        $campaign = CC_Campaign::findOrFail($request->id);
        $campaign->status = $request->status;
        $campaign->save();
        return response()->json(['success' => true]);
    }

    public function uploadPrompt(Request $request){
        $validator = Validator::make($request->all(),[            
            'campaign_id'       => ['required'],
            'prompt'            => 'required|mimes:mp3,wav,ogg|max:10240', // 10MB max size
        ]);
        if($validator->fails()){
            Toastr::error($validator->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }

        $cc_id = Session::get('cc_id');
        $campaign_id = $request->campaign_id;
        $CheckCampaign = CC_Campaign::where('cc_id',$cc_id)->where('campaign_id',$campaign_id)->first();
        if($CheckCampaign){            
            $file_path = './storage/app/public/campaign_prompts/'.$cc_id.'/';
            
            if ( !is_dir( $file_path ) ) {                
                mkdir( $file_path, 0775, true ); // permissions, recursive = true      
            }
            $file = $request->file('prompt');
            $name = $file->getClientOriginalName();
            $promptName = "prompt_".date('ymdhms').".wav";            
            $file->move($file_path,$name);
            $temp_path = $file_path.'/'."$name";
            $path = $file_path.'/'."$promptName";
            $command =  "ffmpeg -i $temp_path -ac 1 -acodec pcm_s16le -ar 8000 $path"; 
            exec($command, $output, $returnVar);
            unlink("$file_path/$name");
            $campaign = CC_Campaign::where('campaign_id',$campaign_id)->first();
            $campaign->prompt = str_replace(".wav","",$promptName);
            $campaign->save();                                      
            Toastr::success('Prompt upload successfully.', 'success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        Toastr::error("Camaign Not Found", 'Error', ["positionClass" => "toast-top-center"]);
        return redirect()->back();

    }

    public function uploadPreviousPrompt(Request $request){
        $validator = Validator::make($request->all(),[            
            'campaign_id'       => ['required'],
            'option'            => ['required']
        ]);
        if($validator->fails()){
            Toastr::error($validator->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }
        $campaign = CC_Campaign::where('campaign_id',$campaign_id)->first();
        $campaign->prompt   = $request->option;
        $campaign->save();

        Toastr::success('Prompt upload successfully.', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();


    }
}
