<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CC_Client_List;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ClientListController extends Controller
{

    public function home(Request $request){
        return view('clientData');
    }

    public function checkSessionStatus(){
        if(empty(Session::get('caller_id'))){
            $response = array(
                'status' => false,
                'msg'    => "Caller id Not Found"
            );
            return response()->json($response, 200);
        }
        $cc_id = Session::get('cc_id');
        $checkClient = CC_Client_List::where('contact',Session::get('caller_id'))->where('cc_id',$cc_id)->first();
        if($checkClient){
            $response = array(
                'status' => true,
                'data'    => $checkClient
            );
            return response()->json($response, 200);
        }
        $response = array(
            'status' => false,
            'msg'    => "Client Data Not Found"
        );
        return response()->json($response, 200);
    }

    public function insertClientData(Request $request){
        $validate = Validator::make($request->all(), [
            'client_name'   => 'required',
            'poc_name'      => 'required',
            'contact'       => 'required',
            'city'          => 'required'
        ]);

        if($validate->fails()){
            Toastr::error($validate->errors(), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        $cc_id = Session::get('cc_id');
        $checkClient = CC_Client_List::where('contact',$request->contact)->where('cc_id',$cc_id)->first();
        if($checkClient){
            Toastr::error("Client Data Already Exists", 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        CC_Client_List::create([
            'client_name'   => $request->client_name,
            'poc_name'      => $request->poc_name,
            'contact'       => $request->contact,
            'city'          => $request->city,
            'cc_id'         => $cc_id
        ]);

        Toastr::success('Client Data Add Successfully....', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();

    }




}
