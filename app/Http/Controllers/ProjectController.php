<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
	public function index()
    {
    	$users = DB::table('users')->get();
    	return view('create_project', ['users' => $users]);
    }

    public function validateValue(Request $request)
    {
    	$group_name = $_POST['group_name'];
    	$group_description = $_POST['description'];
    	if($group_name==null && $group_description==null){
    		return response()->json(["type"=>"error", "message" => "<b>Group Name</b> and <b>Group Description</b> are required"]);
    	} else if ($group_name==null) {
    		return response()->json(["type"=>"error", "message" => "<b>Group Name</b> is required"]);

    	} else if ($group_description==null) {
    		return response()->json(["type"=>"error", "message" => "<b>Group Description</b> is required"]);

    	} else{
    		return response()->json(["type" => "success"]);
    	} 
    
    }

    public function upload(Request $request)
    {
    	$allowed_image_extension = array("png","jpg","jpeg",'webp');
     	$image = $request->file('select_file');

    	if(empty($image)){
			return response()->json(
	        	["type" => "empty", "message" => "Choose image file to upload."]
    		);
    	} else if (!empty($image)) {
    		$file_extension = strtolower($image->getClientOriginalExtension());
    		if(! in_array($file_extension, $allowed_image_extension)){
    			return response()->json(
	           		[ "type" => "error", "message" => "Upload invalid images. Only PNG, JPEG, JPG and WEBP are allowed."]
        		);
    		} else {
    		$new_name = rand() . '.' . $image->getClientOriginalExtension();
    		$image->move(public_path("images"), $new_name);
    			return response()->json(
                	["type" => "success", "message" => "Image uploaded successfully.", "image_path" => 'images/'.$new_name]
            	);
    		}
    	}
    }

    public function store (Request $request)
    {
    	//dd($request);
    	$this->upload($request);

    	$name = $_POST['group_name'];
    	$description = $_POST['description'];
    	$status = $_POST['type_group'];
    	//dd($name_group); 
    	$newgGroup = array('name' => $name, 'description' => $description, 'status' =>'public');
    	
    	DB::table('groups')->insert(['name' => $name, 'description' => $description, 'status' =>'public'])->get;
    	dd($request);

    }

}
