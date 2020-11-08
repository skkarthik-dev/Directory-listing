<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            $file=$request->File('file')->store('public/files/');
            return response()->json(['files'=>$file],200);
        } catch (Exception $e) {
            
        }
    }
}