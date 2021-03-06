<?php

namespace App\Http\Controllers;

use App\Models\Colored;
use App\Models\ColoringCategory;
use App\Models\Fairy;
use App\Models\FairyCategory;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;
use Rawilk\Printing\Facades\Printing;


class MainViewController extends Controller
{
    public function index()
   {
       return view('main.coloring')->with('auth_user',  auth()->user());
   }
   public function profile()
   {
       return view('main.profile')->with('auth_user',  auth()->user());;
   }
    public function new_password()
    {
        return view('new_password')->with('auth_user',  auth()->user());;
    }
    public function front_cat_one()
    {
        return view('main.cat_one')->with('auth_user',  auth()->user());
    }
    public function front_fairy_list()
    {
        return view('main.fairy')->with('auth_user',  auth()->user());
    }
    public function front_video_list()
    {
        return view('main.video')->with('auth_user',  auth()->user());
    }
    public function front_cat_list()
    {
        return view('main.cat')->with('auth_user',  auth()->user());
    }
    public function liked()
    {
        return view('main.liked')->with('auth_user',  auth()->user());
    }
    public function get_one_coloring(Request $request)
    {
        $slug=$request->slug;
        $coloring=Colored::where('slug','=',$slug)->with('categories')->get();
        if ($coloring->isEmpty()) {
            return view('errors.404');
        }
        else
        {
            return view('main.coloring_one')->with('auth_user',  auth()->user())->with(['coloring'=>$coloring]);
        }

    }
    public function get_one_fairy(Request $request)
    {
        $slug=$request->slug;
        $coloring=Fairy::where('slug','=',$slug)->with('categories')->get();
        if ($coloring->isEmpty()) {
            return view('errors.404');
        }
        else
        {
            return view('main.fairy_one')->with(['coloring'=>$coloring]);
        }

    }
    public function get_one_video(Request $request)
    {
        $slug=$request->slug;
        $coloring=Video::where('slug','=',$slug)->with('categories')->get();
        if ($coloring->isEmpty()) {
            return view('errors.404');
        }
        else
        {
            return view('main.video_one')->with(['coloring'=>$coloring]);
        }
    }
    public function front_get_tag_list(Request $request)
    {
        //?? ?????????????????????? ???? ???????????? ??????,????????????, ??????????,?????????? ??????????????
        $type =$request->input('type');
        //??????????????????
        if($type=='coloring')
        {
            $list_tags= ColoringCategory::where('id', '>', 0)->distinct()
                ->whereHas('colored', function ($query) {
                    $query->where('published', '=', 1);
                })
                ->limit(20)->inRandomOrder()->with('categories')->get('category_id');
        }
        if($type=='fairy')
        {
            $list_tags= FairyCategory::where('id', '>', 0)->distinct()
                ->whereHas('fairy', function ($query) {
                    $query->where('published', '=', 1);
                })
                ->limit(20)->inRandomOrder()->with('categories')->get('category_id');
        }
        if($type=='video')
        {
            $list_tags= VideoCategory::where('id', '>', 0)->distinct()
                ->whereHas('video', function ($query) {
                    $query->where('published', '=', 1);
                })
                ->limit(20)->inRandomOrder()->with('categories')->get('category_id');
        }
        return response()->json([
            'status' => 'success',
            'list_tags' => $list_tags,
        ], 200);
    }
    public function fairy_read()
    {
        return view('main.fairy_read');
    }
    public function download($file_name) {
        $file_path = public_path("/images/colorings/".$file_name);
        return response()->download($file_path);
    }
    public function print()
    {
        $printJob = Printing::newPrintTask()
            ->printer()
            ->file('/images/colorings/1_1638717268.jpg')
            ->send();

    }
}
