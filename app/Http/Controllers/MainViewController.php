<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Categories;
use App\Models\Colored;
use App\Models\ColoringCategory;
use App\Models\ColoringUserOption;
use App\Models\Fairy;
use App\Models\FairyCategory;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Nette\Schema\ValidationException;
use Rawilk\Printing\Facades\Printing;


class MainViewController extends Controller
{
    public function index(Request $request)
   {
       $search='';
       if($request->search!=null)
       {
           $search=$request->search;
       }
      // dd($request->key);
       //no h1
       return view('main.coloring')->with('auth_user',  auth()->user())
           ->with(['title'=>'Первая в мире творческая социальная сеть.',
               'description'=>'Творческая социальная сеть для развития детей и помощи родителям. Бесплатные раскраски, известные сказки, популярные мультфильмы и видео.',
               'search_q'=>$search
               ]);
   }
   //список разукрашеннвх раскрасок
   public function decorated_user_list()
   {
       return view('main.decorated_user_list')->with('auth_user',  auth()->user())
           ->with(['title'=>'Список раскрашеннвх пользователем.',
               'description'=>'Творческая социальная сеть для развития детей и помощи родителям. Бесплатные раскраски, известные сказки, популярные мультфильмы и видео.'
           ]);
   }
   public function color_resize()
   {
       $colored_list = Colored::all();

       foreach ($colored_list as $colored)
       {
           if($colored['img_small']=='')
           {

           $path='images/colorings/'.$colored['img'];
           $files = File::get(public_path($path));
           $save_small='1'.'_'.time().'small'.'.jpg';
           Image::make($files)->resize(300,null,function ($constraint) {
               $constraint->aspectRatio();
           })->save(public_path('images/colorings/').$save_small);
           Colored::where('id','=',$colored['id'])->
           update([
               'img_small' => $save_small,
           ]);
               sleep(1);
          }

       }
       $colored_list = ColoringUserOption::all();

       foreach ($colored_list as $colored)
       {
           if($colored['img_small']=='')
           {
           $path='images/colorings/'.$colored['img'];
           $files = File::get(public_path($path));
           $save_small='1'.'_'.time().'small'.'.jpg';
           Image::make($files)->resize(300,null,function ($constraint) {
               $constraint->aspectRatio();
           })->save(public_path('images/colorings/').$save_small);
           ColoringUserOption::where('id','=',$colored['id'])->
           update([
               'img_small' => $save_small,
           ]);
               sleep(1);
         }
       }

   }
   public function add_coloring_user_option(Request $request)
   {
       $slug=$request->slug;
       $name = Colored::where('slug', $slug)
           ->get();
       return view('main.coloring_user_option')
           ->with('auth_user',  auth()->user())
           ->with('slug',  $slug)
           ->with('name',  $name[0]['name'])
           ->with(['title'=>'Первая в мире творческая социальная сеть.',
               'description'=>'Творческая социальная сеть для развития детей и помощи родителям. Бесплатные раскраски, известные сказки, популярные мультфильмы и видео.',

           ]);
   }
   public function success()
   {
       return view('main.success')
           ->with('auth_user',  auth()->user())
           ->with(['title'=>'Спасибо за раскраску',
               'description'=>'Спасибо за добавленную раскраску',
           ]);
   }
   public function add_coloring_user()
   {
       $user = Auth::user();
       $nickname = User::where('id', $user->id)
           ->get('nickname');
       $title='Добавление новой раскраски';
       $description='Страница для добавления новой раскраски в творческой сети virask';
       return view('main.user_add_coloring')->with('auth_user',  auth()->user())->with(['title'=>$title,'description'=>$description]);
   }
   public function profile()
   {
       $user = Auth::user();
       $nickname = User::where('id', $user->id)
           ->get('nickname');
         $title=$nickname[0]['nickname'].' - профиль участника творческой сети virask';
         $description=$nickname[0]['nickname'].' творческая мастерская участника сообщества авторов и пользователей, творческой сети virask';
       return view('main.profile')->with('auth_user',  auth()->user())->with(['title'=>$title,'description'=>$description]);
   }
    public function new_password()
    {
        return view('new_password')->with('auth_user',  auth()->user())->with(['title'=>'Раскраски','description'=>'Распечатай и разукрась свою раскраску']);
    }
    public function front_tag_one(Request $request)
    {
        $slug=$request->slug;
        $coloring=Categories::where('slug','=',$slug)->get();
        if ($coloring->isEmpty()) {
            return view('errors.404');
        }
        else
        {
            $title="бесплатные раскраски -".$coloring[0]['name'];
            $tag_name=$coloring[0]['name'];
            return view('main.tag_one')
                ->with('auth_user',  auth()->user())
                ->with(['title'=>$title,'description'=>$coloring[0]['description'],'tag_name'=>$tag_name]);
        }
    }
    public function front_cat_one(Request $request)
    {
        $slug=$request->slug;
        $coloring=Cat::where('slug','=',$slug)->get();
        if ($coloring->isEmpty()) {
            return view('errors.404');
        }
        else
        {
        $title="бесплатные раскраски -".$coloring[0]['name'];
        $cat_name=$coloring[0]['name'];
        return view('main.cat_one')
            ->with('auth_user',  auth()->user())
            ->with(['title'=>$title,'description'=>$coloring[0]['description'],'cat_name'=>$cat_name]);
        }
    }
    public function front_fairy_list()
    {
        return view('main.fairy')
            ->with('auth_user',  auth()->user())
            ->with(['title'=>'Сказки для детей','description'=>'Сказки для детей любого возраста']);

        ;
    }
    public function front_video_list()
    {
        return view('main.video')
            ->with('auth_user',  auth()->user())
            ->with(['title'=>'Видео для детей','description'=>'Видео для детей любого возраста'])
            ;
    }
    public function front_cat_list()
    {
        return view('main.cat')->with('auth_user',  auth()->user())->with(['title'=>'Каталог бесплатных раскрасок на все случаи жизни.','description'=>'Каталог раскрасок творческой сети virask, популярных мультфильмов, развивающих, обучающих, известных и редких, с помощью каталога можно легко выбрать и бесплатно распечатать или скачать интересную раскраску']);
    }
    public function liked()
    {
        $user = Auth::user();
        $nickname = User::where('id', $user->id)
            ->get('nickname');
        $title=$nickname[0]['nickname'].' - избранные раскраски';
        $description='Избранные раскраски автора творческой сети virask '.$nickname[0]['nickname'];
        return view('main.liked')->with('auth_user',  auth()->user())->with(['title'=>$title,'description'=>$description]);
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
            $coloring_id = $coloring[0]['id'];
//            $colored_id_all = ColoringUserOption::where('id', '=', $coloring_id)->get();
            $same_colorings = ColoringUserOption::
            where('coloring_id', '=',$coloring_id)->where('published', '=','1')->get();
            $count_of_exist=$same_colorings->count();
            $limit=20-$count_of_exist;
            $same_colorings_new = ColoringUserOption::
            where('coloring_id', '!=',$coloring_id)->where('published', '=','1')->limit($limit)->get();
            $same_colorings_new=$same_colorings_new->shuffle();
            $same_colorings=$same_colorings->shuffle();
            $merged = $same_colorings->merge($same_colorings_new);
            return view('main.coloring_one')->
            with('auth_user',  auth()->user())->with([
                'coloring'=>$coloring,
                'title'=>$coloring[0]['name'],
                'description'=>$coloring[0]['description'],
                'same_colorings' => $merged
                ]);
        }

    }
    public function get_one_decorated_coloring(Request $request)
    {
        $slug=$request->slug;
       // return dd($slug);
        $coloring_option=ColoringUserOption::where('slug','=',$slug)->get();
//return dd($coloring);
        if ($coloring_option->isEmpty()) {
            return view('errors.404');
        }
        else {

            $coloring=Colored::where('id','=',$coloring_option[0]['coloring_id'])->with('categories')->get();
            if ($coloring->isEmpty()) {
                return view('errors.404');
            }
            $coloring_id = $coloring_option[0]['id'];
            $colored_id_all = ColoringUserOption::where('id', '=', $coloring_id)->get();
            $same_colorings = ColoringUserOption::
            where('coloring_id', '=', $colored_id_all[0]['coloring_id'])
                ->where('id', '!=', $colored_id_all[0]['id'])
                ->where('published', '=','1')
                ->get();

            $count_of_exist=$same_colorings->count();
            $limit=20-$count_of_exist;
            $same_colorings_new = ColoringUserOption::
            where('coloring_id', '!=',$colored_id_all[0]['coloring_id'])->where('published', '=','1')->limit($limit)->get();
            $same_colorings_new=$same_colorings_new->shuffle();
            $same_colorings=$same_colorings->shuffle();
            $merged = $same_colorings->merge($same_colorings_new);
            $title=$coloring[0]['name'] .' Разукрашенная, версия '.$coloring_option[0]['id'];
            $description=$coloring[0]['description'].' Разукрашенная, версия '.$coloring_option[0]['id'].', разукрасил '.$coloring_option[0]['user_name'];
            return view('main.coloring_decorated_one')->with('auth_user', auth()->user())
                ->with(
                    [   'coloring' => $coloring,'coloring_decorated' => $coloring_option,
                        'title' => $title, 'description' => $description,
                        'slugMain' => $coloring[0]['slug'],
                        'same_colorings' => $merged],
                );
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
        //в зависимости от раскра ски,сказки, видео,будет выборка
        $type =$request->input('type');
        //раскраски
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
