<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Nette\Schema\ValidationException;

class VideoController extends Controller
{
    public function upload_video(Request $request)
    {
        $user = Auth::user();
        $name = $request->input('name');
        $description = $request->input('description');
        $published = $request->input('published');
        $video_link = $request->input('video_link');
        if($published=="true")
        {
            $published=1;
        }
        else
        {
            $published=0;
        }
        $selected_category = $request->input('selected_category');
        try {
            $this->validate($request,[
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'name'=> 'required|string|min:5|max:40',
                'description'=> 'required|string|min:10|max:300',
                'video_link'=> 'required|string',
                'selected_category'=> 'required',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        $image = request()->file();
        $save_to=$user->id.'_'.time().'.jpg';
        Image::make($request->file)->save(public_path('images/video/').$save_to);
        $colored_id= Video::create([
            'name'=>$name,
            'image' => $save_to,
            'video_link' => $video_link,
            'description' => $description,
            'from_user' => $user->id,
            'published'=>$published
        ]);

       $myArray = explode(',', $selected_category);

        foreach ($myArray as $colored)
        {
            VideoCategory::create([
                'category_id'=>$colored,
                'video_id' => $colored_id['id'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message'    => 'Видео создана',
            'video_id'    => $colored_id['id'],
            //тут id
            'selected_category'=>$selected_category
        ], 201);
    }

    public function get_edit_video(Request $request)
    {
        $color_id =  $request->input('video_id');
        $list_colored = Video::where('id', '=', $color_id)
            ->with('categories')
            ->get();
        if($list_colored)
        {
            return $list_colored;
        }
        return response()->json([
            'status' => 'error',
            'message'    => 'Error',
        ], 422);
    }
    public function save_edit_video(Request $request)
    {
        $user = Auth::user();
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $published = $request->input('published');
        $video_link = $request->input('video_link');
        if($published=="true")
        {
            $published=1;
        }
        else
        {
            $published=0;
        }
        $selected_category = $request->input('selected_category');
        $file = request()->file();;
        try {
            $this->validate($request,[
                'name'=> 'required|string|min:5|max:40',
                'description'=> 'required|string|min:10|max:300',
                'video_link'=> 'required|string',
                'selected_category'=> 'required',
            ]);
            if($file)
            {
                $this->validate($request,[
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
                ]);
            }
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        if($file) {

            $to_del = Video::where('id','=',$id)->get();
            $path = public_path()."/images/video/".$to_del[0]['image'];
            unlink($path);
            $image = request()->file();
            $save_to = $user->id . '_' . time() . '.jpg';
            Image::make($request->file)->save(public_path('images/video/') . $save_to);
            $colored_id= Video::where('id','=',$id)->
            update([
                'name'=>$name,
                'video_link' => $video_link,
                'image' => $save_to,
                'description' => $description,
                'from_user' => $user->id,
                'published'=>$published
            ]);
        }
        else
        {
            $colored_id= Video::where('id','=',$id)->
            update([
                'name'=>$name,
                'video_link' => $video_link,
                'description' => $description,
                'from_user' => $user->id,
                'published'=>$published
            ]);
        }



       $myArray = explode(',', $selected_category);
        VideoCategory::where('video_id','=',$id)->delete();

        foreach ($myArray as $colored)
        {
            VideoCategory::create([
                'category_id'=>$colored,
                'video_id' => $id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message'    => 'Видео создана',
            'video_id'    => $id,
            //тут id
            'selected_category'=>$selected_category
        ], 201);
    }
    public function get_video_list(Request $request)
    {
//        $published =  $request->input('published');
        $offset =  $request->input('offset');
        $search_id =  $request->input('search_id');
        $front =  $request->input('front');

        //если идет поиск по тегу
        if($search_id)
        {

            $list_colored = VideoCategory::where('category_id', '=', $search_id)
                ->offset($offset)
                ->whereHas('video', function ($query) {
                    $query->where('published', '=', 1);
                })
                ->limit(12)
                ->get();
            $col_res=[];
            foreach ($list_colored as $col)
            {
                $col_res[]=$col['video'];
            }
            $col_res=collect($col_res);
            $count = VideoCategory::where('category_id', '=', $search_id)
                ->whereHas('video', function ($query) {
                    $query->where('published', '=', 1);
                })
                ->count();
            return response()->json([
                'status' => 'success',
                'list_colored' => $col_res,
                'tipes_count' => $count,
            ], 201);
        }

        $list_colored = Video::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->offset($offset)
            ->limit(12)
            ->get();
        $count = Video::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->count();
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
        ], 201);


    }
    public function moderation_video(Request $request)
    {
        $id=  $request->input('id');
        $published =  $request->input('published');
        Video::where('id','=',$id)->
        update([
            'published' => $published,
        ]);
    }
    public function delete_video(Request $request)
    {
        $fairy_id =  $request->input('id');
        VideoCategory::where('video_id', '=', $fairy_id) ->delete();
        $to_del = Video::where('id','=',$fairy_id)->get();
        $path = public_path()."/images/video/".$to_del[0]['image'];
        try
        {
            unlink($path);
        }
        catch(Throwable $e)
        {
        }
        Video::where('id','=',$fairy_id)->delete();
        return response()->json([
            'status' => 'success',
        ], 201);
    }
}
