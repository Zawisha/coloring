<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Categories;
use App\Models\Colored;
use App\Models\ColoringCat;
use App\Models\ColoringCategory;
use App\Models\ColoringUserOption;
use App\Models\Like;
use App\Models\LikeCounter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColoringController extends Controller
{
//    public function index()
//    {
//        return view('sections.coloring');
//    }
    public function get_categories(Request $request)
    {
        //unidev comment
        $queryString=$request->input('req');
        $categories = Categories::where('name', 'LIKE', "%$queryString%")->orderBy('name')->get();
        $categories_old = Categories::where('name', $queryString)->get();
        if ($categories_old->isEmpty()) {
            $categories->push(['name' => $request->input('req'), 'id' => 'user_tag']);
        }
        return $categories;
    }

    public function get_coloring_names(Request $request)
    {
        $queryString=$request->input('req');
       $coloring = Colored::where('name', 'LIKE', "%$queryString%")->where('published', '=', 1)->orderBy('name')->get();
      //  $categories_old = Colored::where('name', $queryString)->get();
        if ($coloring->isEmpty()) {
            //  $categories_old = Colored::where('name', $queryString)->get();
            $coloring->push(['name' => $request->input('req'), 'id' => 'user_tag']);
            return $coloring;
        }
//        dd(count($coloring));
        if(count($coloring)!=1)
        {
            $coloring[count($coloring)-1]=$coloring[0];
            $coloring[0]=(['name' => $request->input('req'), 'id' => 'user_tag']);
        }
        else
        {
            $temp_col=$coloring[0];
            $coloring[0]=(['name' => $request->input('req'), 'id' => 'user_tag']);
            $coloring->push($temp_col);
        }
        return $coloring;
    }

    public function coloring_list()
    {
        return view('admin.coloring_list');
    }
    public function admin_decorated_coloring_list()
    {
        return view('admin.admin_decorated_coloring_list');
    }
    public function moderation_color(Request $request)
    {
        $id=  $request->input('id');
        $published =  $request->input('published');
        Colored::where('id','=',$id)->
        update([
            'published' => $published,
        ]);
    }
    public function moderation_decorated_color(Request $request)
    {
        $id=  $request->input('id');
        $published =  $request->input('published');
        ColoringUserOption::where('id','=',$id)->
        update([
            'published' => $published,
        ]);
    }

   public function get_coloring_list_by_cat(Request $request)
    {
        $search_slug =  $request->input('search_slug');
        $offset =  $request->input('offset');
        //получаем id категории
        $cat_id = Cat::where('slug', $search_slug)
            ->first('id');
        $cat_list = ColoringCat::where('cat_id', $cat_id['id'])
            ->offset($offset)
            ->limit(20)
            ->whereHas('colored', function ($query) {
                $query->where('published', '=', 1);
            })
            ->with('colored')
            ->get();
        $count = ColoringCat::where('cat_id', $cat_id['id'])
            ->whereHas('colored', function ($query) {
                $query->where('published', '=', 1);
            })
            ->count();
       $col_ids_arr=[];
       foreach ($cat_list as $one_colored)
       {
           array_push($col_ids_arr, $one_colored['id']);
       }
       $list_like=[];
       $userId = Auth::id();
       if(Auth::id())
       {
           $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
       }
       $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
       foreach ($cat_list as $key=>$colored)
       {
           $colored['type_of_like']='0';
           $colored['count_of_like']='0';
           foreach ($list_like as $like)
           {
               if($colored['id']==$like['post_id'])
               {
                   $cat_list[$key]['type_of_like']=$like['type_of_like'];
               }
           }
           foreach ($list_like_counter as $like_count)
           {
               if($colored['id']==$like_count['post_id'])
               {
                   $cat_list[$key]['count_of_like']=$like_count['count_of_like'];
               }
           }
       }
       return response()->json([
           'status' => 'success',
           'list_colored' => $cat_list,
           'like_arr' => $list_like,
           'tipes_count' => $count
       ], 201);
    }
    public function get_coloring_list_by_tag(Request $request)
    {
        $search_slug =  $request->input('search_slug');
        $offset =  $request->input('offset');
        //получаем id категории
        $cat_id = Categories::where('slug', $search_slug)
            ->first('id');
        $cat_list = ColoringCategory::where('category_id', $cat_id['id'])
            ->offset($offset)
            ->limit(20)
            ->whereHas('colored', function ($query) {
                $query->where('published', '=', 1);
            })
            ->with('colored')
            ->get();
        $count = ColoringCategory::where('category_id', $cat_id['id'])
            ->whereHas('colored', function ($query) {
                $query->where('published', '=', 1);
            })
            ->count();
        $col_ids_arr=[];
        foreach ($cat_list as $one_colored)
        {
            array_push($col_ids_arr, $one_colored['id']);
        }
        $list_like=[];
        $userId = Auth::id();
        if(Auth::id())
        {
            $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
        }
        $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
        foreach ($cat_list as $key=>$colored)
        {
            $colored['type_of_like']='0';
            $colored['count_of_like']='0';
            foreach ($list_like as $like)
            {
                if($colored['id']==$like['post_id'])
                {
                    $cat_list[$key]['type_of_like']=$like['type_of_like'];
                }
            }
            foreach ($list_like_counter as $like_count)
            {
                if($colored['id']==$like_count['post_id'])
                {
                    $cat_list[$key]['count_of_like']=$like_count['count_of_like'];
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'list_colored' => $cat_list,
            'like_arr' => $list_like,
            'tipes_count' => $count
        ], 201);
    }

    public function get_coloring_list(Request $request)
    {
        $offset =  $request->input('offset');
        $search_id =  $request->input('search_id');
        $front =  $request->input('front');
        $search_q =  $request->input('search_q');
        //если идет поиск по тегу
        if($search_id)
{
    $list_colored = ColoringCategory::where('category_id', '=', $search_id)
        ->offset($offset)
        ->limit(10)
        ->whereHas('colored', function ($query) {
            $query->where('published', '=', 1);
        })
        ->get();
    $col_res=[];
    foreach ($list_colored as $col)
    {
        $col_res[]=$col['colored'];
    }
    $col_res=collect($col_res);
    $count = ColoringCategory::where('category_id', '=', $search_id)
        ->whereHas('colored', function ($query) {
            $query->where('published', '=', 1);
        })
        ->count();
    return response()->json([
        'status' => 'success',
        'list_colored' => $col_res,
        'tipes_count' => $count,
    ], 201);
}
        //счетчик
//общий случай без тегов
        $count=0;
        //если включён поиск
        if($search_q)
        {
            $list_colored =
                Colored::where('name', 'LIKE', "%$search_q%")
                ->where('published','=', '1')
                ->offset($offset)
                ->limit(10)
                ->orderBy('id', 'desc')
                ->get();

            $count =
                Colored::where('name', 'LIKE', "%$search_q%")
                ->when($front, function($q){
                    return $q->where('published','=', '1');
                })
                ->count();
        }
        //если в поиске нету результатов или вообще нет поиска
       if($count==0) {
           $list_colored = Colored::where('id', '>', '0')
               ->when($front, function ($q) {
                   return $q->where('published', '=', '1');
               })
               ->offset($offset)
               ->limit(10)
               ->orderBy('id', 'desc')
               ->get();

           $count = Colored::where('id', '>', '0')
               ->when($front, function ($q) {
                   return $q->where('published', '=', '1');
               })
               ->count();
       }


//        return dd($list_colored);
        $col_ids_arr=[];
        foreach ($list_colored as $one_colored)
        {
            array_push($col_ids_arr, $one_colored['id']);
        }
        $list_like=[];
        $userId = Auth::id();
        if(Auth::id())
        {
        $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
        }
        $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
        foreach ($list_colored as $key=>$colored)
        {
            $colored['type_of_like']='0';
            $colored['count_of_like']='0';
            foreach ($list_like as $like)
            {
                if($colored['id']==$like['post_id'])
                {
                    $list_colored[$key]['type_of_like']=$like['type_of_like'];
                }
            }
            foreach ($list_like_counter as $like_count)
            {
                if($colored['id']==$like_count['post_id'])
                {
                    $list_colored[$key]['count_of_like']=$like_count['count_of_like'];
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
            'like_arr' => $list_like,
        ], 201);
    }
    public function get_coloring_list_liked(Request $request)
    {
        $offset =  $request->input('offset');
        $search_id =  $request->input('search_id');
        $front =  $request->input('front');
        $user = Auth::user();
//        $user->id;
        $like_user = like::where('user_id',  $user->id)
          ->where('type_of_like','1')
          ->where('type_of_content','1')
            ->offset($offset)
            ->limit(20)
          ->get('post_id');
        $count = like::where('user_id',  $user->id)
            ->where('type_of_like','1')
            ->where('type_of_content','1')
            ->count();
        $list_colored = Colored::whereIn('id', $like_user)
            ->get();
//        $count = Colored::whereIn('id', $like_user)
//            ->count();
        $col_ids_arr=[];
        foreach ($list_colored as $one_colored)
        {
            array_push($col_ids_arr, $one_colored['id']);
        }
        $list_like=[];
        $userId = Auth::id();
        if(Auth::id())
        {
            $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
        }
        $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
        foreach ($list_colored as $key=>$colored)
        {
            $colored['type_of_like']='0';
            $colored['count_of_like']='0';
            foreach ($list_like as $like)
            {
                if($colored['id']==$like['post_id'])
                {
                    $list_colored[$key]['type_of_like']=$like['type_of_like'];
                }
            }
            foreach ($list_like_counter as $like_count)
            {
                if($colored['id']==$like_count['post_id'])
                {
                    $list_colored[$key]['count_of_like']=$like_count['count_of_like'];
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
            'like_arr' => $list_like,
        ], 201);
    }
    public function get_user_decorated_coloring_list(Request $request)
    {
        $offset =  $request->input('offset');
        $user = Auth::user();

        $list_colored = ColoringUserOption::where('user_id', $user->id)
            ->offset($offset)
            ->limit(20)
            ->orderBy('id', 'desc')
            ->get();
        foreach ($list_colored as $colored)
        {
            $one_color = Colored::where('id', $colored['coloring_id'])->get();
            //добавить название раскраски старой к новой раскрашенной чтобы вывести на фронте
            $colored['name_old']=$one_color[0]['name'];
        }
        $count = ColoringUserOption::where('user_id', $user->id)->count();

        //работа с like
        $col_ids_arr=[];
        foreach ($list_colored as $one_colored)
        {
            array_push($col_ids_arr, $one_colored['id']);
        }
        $list_like=[];
        $userId = Auth::id();
        if(Auth::id())
        {
            $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '3')->get();
        }
        $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '3')->get();
        foreach ($list_colored as $key=>$colored)
        {
            $colored['type_of_like']='0';
            $colored['count_of_like']='0';
            foreach ($list_like as $like)
            {
                if($colored['id']==$like['post_id'])
                {
                    $list_colored[$key]['type_of_like']=$like['type_of_like'];
                }
            }
            foreach ($list_like_counter as $like_count)
            {
                if($colored['id']==$like_count['post_id'])
                {
                    $list_colored[$key]['count_of_like']=$like_count['count_of_like'];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
            'like_arr' => $list_like,
        ], 201);
    }

    public function get_decorated_coloring_list(Request $request)
    {
        $offset =  $request->input('offset');

            $list_colored = ColoringUserOption::where('id', '>', '0')
                ->offset($offset)
                ->limit(20)
                ->orderBy('id', 'desc')
                ->get();
            foreach ($list_colored as $colored)
            {
                $one_color = Colored::where('id', $colored['coloring_id'])->get();
                //добавить название раскраски старой к новой раскрашенной чтобы вывести на фронте
                $colored['name_old']=$one_color[0]['name'];
            }
            $count = ColoringUserOption::where('id', '>', '0')->count();

        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
        ], 201);
    }
    public function getColoringDecoratedLike(Request $request)
    {
        $slug=$request->colorSlug;
        $type_of_content=$request->type_of_content;
        $id=$request->id;
        $count_of_like='0';
        $one_type_of_like='';
        //общее количество лайков
        $like_counter = LikeCounter::where('post_id', $id)->where('type_of_content', $type_of_content)->get();
        if($like_counter->isEmpty())
        {
            $count_of_like='0';
        }
        else
        {
            $count_of_like=$like_counter[0]['count_of_like'];
        }
        $userId = Auth::id();
        if($userId!==null)
        {
            //вытаскиваем состояние его лайка
            $like_status = Like::where('post_id', '=',$id)->where('user_id','=', $userId)->where('type_of_content','=', $type_of_content)->get();
            if($like_status->isEmpty())
            {
                $one_type_of_like='0';
            }
            //если лайк уже был поставлен этим пользователем
            else
            {
                $one_type_of_like=$like_status[0]['type_of_like'];

            }
        }
        else
        {
            $one_type_of_like='0';
        }

        return response()->json([
            'status' => 'success',
            'one_type_of_like' => $one_type_of_like,
            'count_of_like' => $count_of_like,
        ], 200);
    }
    public function getOneFrontColoring(Request $request)
    {
        $slug=$request->colorSlug;
        $coloring=Colored::where('slug','=',$slug)->get();
//                return dd($coloring[0]['id']);
        //общее количество лайков
        $like_counter = LikeCounter::where('post_id', $coloring[0]['id'])->where('type_of_content', '1')->get();
//        return dd($like_counter[0]['count_of_like']);
        if($like_counter->isEmpty())
        {
            $coloring[0]['count_of_like']='0';
        }
        else
        {
            $coloring[0]['count_of_like']=$like_counter[0]['count_of_like'];
        }
//        return dd(Auth::id());
        $userId = Auth::id();
        if($userId!==null)
        {
            //вытаскиваем состояние его лайка
            $like_status = Like::where('post_id', '=',$coloring[0]['id'])->where('user_id','=', $userId)->where('type_of_content','=', '1')->get();
            if($like_status->isEmpty())
            {
                $coloring[0]['type_of_like']='0';
            }
            //если лайк уже был поставлен этим пользователем
            else
            {
                $coloring[0]['type_of_like']=$like_status[0]['type_of_like'];

            }
        }
        else
        {
            $coloring[0]['type_of_like']='0';
        }
        $user_name = User::where('id', '=',$coloring[0]['from_user'])->get('nickname');
        $coloring[0]['nickname']=$user_name[0]['nickname'];
        $cat = ColoringCat::where('colored_id', '=',$coloring[0]['id'])->limit(6)->with('cat')->get();
        $category = ColoringCategory::where('colored_id', '=',$coloring[0]['id'])->limit(6)->with('categories')->get();
        return response()->json([
            'status' => 'success',
            'coloring' => $coloring,
            'cat' => $cat,
            'category' => $category
        ], 200);
    }

    public function get_edit_color(Request $request)
    {
        $color_id =  $request->input('color_id');

        $list_colored = Colored::where('id', '=', $color_id)
            ->with('categories')
            ->with('cat')
            ->get();

        if($list_colored)
        {
            if($list_colored[0]['published']==0)
            {
                $list_colored[0]['published']=false;
            }
            else
            {
                $list_colored[0]['published']=true;
            }

            return $list_colored;
        }
        return response()->json([
            'status' => 'error',
            'message'    => 'Error',
        ], 422);
    }

    public function delete_colored(Request $request)
    {
        $fairy_id =  $request->input('id');
        $to_del = Colored::where('id','=',$fairy_id)->get();

        try {
            $path = public_path() . "/images/colorings/" . $to_del[0]['img'];
            unlink($path);
            $path = public_path() . "/images/colorings/" . $to_del[0]['img_small'];
            unlink($path);
        }
        catch (\Throwable $e)
        {

        }
        ColoringCategory::where('colored_id', '=', $fairy_id) ->delete();
        ColoringCat::where('colored_id', '=', $fairy_id) ->delete();
        Colored::where('id','=',$fairy_id)->delete();


        return response()->json([
            'status' => 'success',
        ], 201);
    }
    public function delete_colored_decoration(Request $request)
    {
        $fairy_id =  $request->input('id');
        $to_del = ColoringUserOption::where('id','=',$fairy_id)->get();

        try {
            $path = public_path() . "/images/colorings/" . $to_del[0]['img'];
            unlink($path);
            $path = public_path() . "/images/colorings/" . $to_del[0]['img_small'];
            unlink($path);
        }
        catch (\Throwable $e)
        {

        }
        ColoringUserOption::where('id','=',$fairy_id)->delete();

        return response()->json([
            'status' => 'success',
        ], 201);
    }
}
