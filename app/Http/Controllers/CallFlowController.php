<?php

namespace App\Http\Controllers;

use App\Models\CC_Admin;
use App\Models\CC_Queue;
use App\Models\CC_Sounds;
use Illuminate\Http\Request;
use App\Models\CC_Multilayer;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CallFlowController extends Controller
{
    public function home(){
        return view('soundSetting');
    }

    public function uploadSounds(Request $request){
        $validate = Validator::make($request->all(), [
            'layers'      => 'required',
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }        
        $cc_id = Session::get('cc_id');
        $no_of_layers = $request->layers;              
        $audios = array('welcome');                
        $file_path = './storage/app/public/prompts/'.$cc_id.'/';
        if ( !is_dir( $file_path ) ) {                
            mkdir( $file_path, 0775, true ); // permissions, recursive = true      
        }
        if ( !is_dir( "/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id" ) ) {                
            mkdir( "/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id", 0777, true ); // permissions, recursive = true      
        }
        $audio_is_exists = CC_sounds::where('cc_id',$cc_id)->delete();        
        for($i = 0; $i<$no_of_layers; $i++){            
            if($no_of_layers == 1){                
                $welcome = $request->file('welcome');
                $name = $welcome->getClientOriginalName();
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $name = pathinfo($name, PATHINFO_FILENAME);                
                $name = $name.date('ymdhms').".".$extension;
                $welcome->move($file_path,$name);
                $temp_path = $file_path.'/'."$name";
                $path = $file_path.'/'."welcome.wav";
                if(file_exists($path)){
                    unlink($path);
                }
                $command =  "ffmpeg -i $temp_path -ac 1 -acodec pcm_s16le -ar 8000 $path";                 
                exec($command, $output, $returnVar);                
                unlink("$file_path/$name");                
                File::copy($path,"/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id/welcome.wav");
                CC_Sounds::create([
                    'cc_id'     => $cc_id,
                    'file_name' => 'welcome',
                    'path'      => $path
                ]);
                Toastr::success('Prompt upload successfully.', 'success', ["positionClass" => "toast-top-right"]);
                return redirect('call-flow/sound-settings');                                         
            }
            if($i >= 1){
                array_push($audios,"mainmenu$i");
            }            
        }
        foreach($audios as $prompt){
            $file = $request->file($prompt);
            $name = $file->getClientOriginalName();
            $extension = pathinfo($name, PATHINFO_EXTENSION);        
            $name = pathinfo($name, PATHINFO_FILENAME);            
            $name = $name.date('ymdhms').".".$extension;
            $file->move($file_path,$name);            
            $temp_path = $file_path.'/'."$name";
            $path = $file_path.'/'."$prompt.wav";
            if(file_exists($path)){
                unlink($path);
            }
            $command =  "ffmpeg -i $temp_path -ac 1 -acodec pcm_s16le -ar 8000 $path";                 
            exec($command, $output, $returnVar);                
            unlink("$file_path/$name");
            File::copy($path,"/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id/$prompt.wav");            
            CC_Sounds::create([
                'cc_id'     => $cc_id,
                'file_name' => $prompt.'.wav',
                'path'      => $path
            ]);            
        }
        Toastr::success('Prompt upload successfully.', 'success', ["positionClass" => "toast-top-right"]);
        return redirect('call-flow/sound-settings');
    }

    public function showFlow(Request $request){
        $cc_id = Session::get('cc_id');
        $no_of_audios = CC_Sounds::where('cc_id',$cc_id)->count();
        $queue = CC_Queue::where('cc_id',$cc_id)->get();
        $formattedFlow = CC_Multilayer::where('cc_id', $cc_id)->get();
        $existingFlow = [];
        foreach ($formattedFlow as $item) {
            $layerKey = $item->layers; // e.g., "layer1", "layer2"

            // Convert to array and filter only the option fields
            $options = collect($item->toArray())
                ->filter(function ($value, $key) {
                    return !is_null($value) && str_starts_with($key, 'option');
                });

            $existingFlow[$layerKey] = $options->toArray();
        }
        return view('callFlow',compact('no_of_audios','queue','existingFlow'));
    }

    public function queue(Request $request){
        $cc_id = Session::get('cc_id');
        $queue = CC_Queue::where('cc_id',$cc_id)->orderBy('created_at','DESC')->paginate(10);
        $extensions = CC_Admin::where('cc_id',$cc_id)->get();
        foreach($queue as $value){                        
            $Extensions_array = CC_Admin::select('agent_exten')
            ->whereIn('admin_id',explode(",",$value->admin_id))
            ->orderByRaw('FIELD(admin_id, ' . $value->admin_id . ')')
            ->get();
            $agent_extension = [];
            foreach ($Extensions_array as $agent) {
                $agent_extension[] = $agent->agent_exten;
            }
            $value->agent_exten = implode(',',$agent_extension);                                    
        }
        
        return view('queue',compact('queue','extensions'));
    }

    public function createQueue(Request $request){
        $validate = Validator::make($request->all(), [
            'queue_name'    => 'required',
            'extensions'    => 'required',
            'queue_type'    => 'required',
            
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }      
        $extensions = implode(',',json_decode($request->ordered_extensions));        
        $cc_id = Session::get('cc_id');
        $i = 1;
        foreach(json_decode($request->ordered_extensions) as $value){
           $agent = CC_Admin::where('cc_id',$cc_id)->where('admin_id',$value)->first();
           $agent->priority = $i;
           $agent->save();
           $i++; 
        }
        CC_Queue::create([
            'cc_id'         => $cc_id,
            'admin_id'      => $extensions,
            'queue_name'    => $request->queue_name,
            'queue_type'    => $request->queue_type,
            'queue_status'  => 1
        ]);
        Toastr::success('Queue Add Successfully....', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function create_call_flow(Request $request){
        $validate = Validator::make($request->all(), [
            'cc_id'      => 'required',            
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);            
        }
        CC_Multilayer::where('cc_id',$request->cc_id)->delete();        
        $j = 0;
        foreach($request->allLayers as $key => $layer){
            if($key == "layer1"){
                $prompt = "welcome";
            }else{
                $prompt = "mainmenu$j";
            }
            CC_Multilayer::create([
                'cc_id'     => $request->cc_id,
                'layers'    => $key,
                'prompt'    => $prompt,
                'option0'   => isset($layer['option0']) ? $layer['option0'] : null,
                'option1'   => isset($layer['option1']) ? $layer['option1'] : null,
                'option2'   => isset($layer['option2']) ? $layer['option2'] : null,
                'option3'   => isset($layer['option3']) ? $layer['option3'] : null,
                'option4'   => isset($layer['option4']) ? $layer['option4'] : null,
                'option5'   => isset($layer['option5']) ? $layer['option5'] : null,
                'option6'   => isset($layer['option6']) ? $layer['option6'] : null,
                'option7'   => isset($layer['option7']) ? $layer['option7'] : null,
                'option8'   => isset($layer['option8']) ? $layer['option8'] : null,
                'option9'   => isset($layer['option9']) ? $layer['option9'] : null,
                'option10'  => isset($layer['option10']) ? $layer['option10'] : null,
                'option11'  => isset($layer['option11']) ? $layer['option11'] : null,
                'option12'  => isset($layer['option12']) ? $layer['option12'] : null,
                'option13'  => isset($layer['option13']) ? $layer['option13'] : null,
                'option14'  => isset($layer['option14']) ? $layer['option14'] : null,
                'option15'  => isset($layer['option15']) ? $layer['option15'] : null,
                'option16'  => isset($layer['option16']) ? $layer['option16'] : null,
                'option17'  => isset($layer['option17']) ? $layer['option17'] : null,
                'option18'  => isset($layer['option18']) ? $layer['option18'] : null,
                'option19'  => isset($layer['option19']) ? $layer['option19'] : null,
                'option20'  => isset($layer['option20']) ? $layer['option20'] : null,
                'optiont'   => isset($layer['optionT']) ? $layer['optionT'] : null,
                'option*'   => isset($layer['optionStar']) ? $layer['optionStar'] : null,
                'option#'   => isset($layer['optionHash']) ? $layer['optionHash'] : null
            ]);
            $j++;
        }
        Toastr::success('Prompt upload successfully.', 'success', ["positionClass" => "toast-top-right"]);        
    }

    public function uploadOfftime(Request $request){
        $validate = Validator::make($request->all(), [
            'layers'      => 'required',
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }      
        $cc_id = Session::get('cc_id');
        $no_of_layers = $request->layers;                                   
        $file_path = './storage/app/public/prompts/'.$cc_id.'/';
        if ( !is_dir( $file_path ) ) {                
            mkdir( $file_path, 0775, true ); // permissions, recursive = true      
        }
        if ( !is_dir( "/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id" ) ) {                
            mkdir( "/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id", 0777, true ); // permissions, recursive = true      
        }
        $welcome = $request->file('ivr');
        $name = $welcome->getClientOriginalName();
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $name = pathinfo($name, PATHINFO_FILENAME);                
        $name = $name.date('ymdhms').".".$extension;
        $welcome->move($file_path,$name);
        $temp_path = $file_path.'/'."$name";
        $path = $file_path.'/'."ivr.wav";
        $command =  "ffmpeg -i $temp_path -ac 1 -acodec pcm_s16le -ar 8000 $path";                 
        exec($command, $output, $returnVar);                
        unlink("$file_path/$name");                
        File::copy($path,"/var/lib/asterisk/sounds/usr_sounds/dev_cc/$cc_id/ivr.wav");
        CC_Sounds::create([
            'cc_id'     => $cc_id,
            'file_name' => 'ivr',
            'path'      => $path
        ]);
        Toastr::success('Prompt upload successfully.', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function showqueue($queue_id){
        $cc_id = Session::get('cc_id');
        $queue = CC_Queue::where('cc_id',$cc_id)->where('queue_id',$queue_id)->first();
        $extensions = CC_Admin::where('cc_id',$cc_id)->get();                
        $Extensions_array = CC_Admin::select('agent_exten')
            ->whereIn('admin_id',explode(",",$queue->admin_id))
            ->orderByRaw('FIELD(admin_id, ' . $queue->admin_id . ')')
            ->get();        
        $agent_extension = [];
        foreach ($Extensions_array as $agent) {            
            $agent_extension[] = $agent->agent_exten;
        }        
        $queue->agent_exten = implode(',',$agent_extension);                             
        return view('update-queue',compact('queue','extensions'));
    }

    public function updateQueue(Request $request){        
        $validate = Validator::make($request->all(), [
            'queue_id'              => 'required',
            'queue_name'            => 'required',
            'ordered_extensions'    => 'required',
            'queue_type'            => 'required',            
        ]);
        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }        
        $get_queue = CC_Queue::where('queue_id',$request->queue_id)->first();
        if($get_queue){
            $get_queue->admin_id    = $request->ordered_extensions;
            $get_queue->queue_name  = $request->queue_name;
            $get_queue->queue_type  = $request->queue_type;
            $get_queue->save();
            Toastr::success('Queue Updated Successfully....', 'success', ["positionClass" => "toast-top-right"]);
            return redirect("call-flow/queue");
        }
        Toastr::error('Queue Updated Failed....', 'success', ["positionClass" => "toast-top-right"]);
        return redirect("call-flow/queue");
    }

    public function delete($queue_id){
        $get_queue = CC_Queue::where('queue_id',$queue_id)->first();
        if($get_queue){
            CC_Queue::where('queue_id',$queue_id)->delete();
            Toastr::success('Queue Deleted Successfully....', 'success', ["positionClass" => "toast-top-right"]);
            return redirect("call-flow/queue");    
        }
        Toastr::error('Queue Deleted Failed....', 'success', ["positionClass" => "toast-top-right"]);
        return redirect("call-flow/queue");
    }
}
