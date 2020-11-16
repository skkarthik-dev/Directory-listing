<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UploadHistory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function displayFiles(Request $request)
    {
        try {
            $del_val = [".",".."];
            $files_path = base_path('storage/app/public/files/');
            if(isset($request->file_search))
            {
                $ext = explode(".", $request->file_search);
                if(isset($ext[1]))
                 {
                    $files = array_map('basename', glob($files_path."*.".$ext[1]));
                 }
                 else
                 {
                    $files = scandir($files_path); 
                 }

                $input = preg_quote($ext[0], '~');
                $files = collect(preg_grep('~' . $input . '~', $files));
            }
            else
            {
                $files = scandir($files_path);
                $files = collect(array_diff($files, $del_val)); 
            }
            
            $files = $files->paginate(10);
            $link = (string)$files->links();
            return response()->json(['files'=>$files,'link'=>$link],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    public function uploadFiles(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:2048',
            ],[
            'file.required' => "Please Select a File to Upload",
            'file.file' => "Invalid file type",
            'file.max' => "Allowed file size is :max",
            ]);
            if($validator->fails()){
            return response($validator->messages(), 400);
            } else {
                $file_path = storage_path().'/app/public/files/';
                if(!File::exists($file_path)){
                   $var = File::makeDirectory($file_path,$mode = 0777, true, true);
                }
                $file=$request->File('file')->store('public/files');
                $upload = new UploadHistory();
                $upload->actual_name = $request->file('file')->getClientOriginalName();
                $upload->created_name = $file;
                $upload->save();
                return response()->json(['files'=>$file],200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            $files_path = base_path('storage/app/public/files/');
           $file =  UploadHistory::where('created_name','public/files/'.$request->file)->first();
           if($file == null)
           {
                return response()->json(['error' => "Please provide valid File"],400);
           }
           else
           {
                $file->delete();
                unlink($files_path.$request->file);
                return response()->json(['success'=>1,'message'=>'File has been deleted successfully'],200);
           }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],400);
        }
    }
}
