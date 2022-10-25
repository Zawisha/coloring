<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarText;
use App\Models\VkSearchGroup;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function helper()
    {
        return view('main.car');
    }
    public function check_user(Request $request)
    {
        $user_id =  $request->input('user_id');
        $picked =  $request->input('picked');
        $user_id=trim($user_id);
        $group_post_last=Car::where('user_id',$user_id)->get();
        if (!($group_post_last->isEmpty())) {
            return response()->json([
                'status' => 'success',
                'isset' => 'уже был',
            ], 200);
        }
        else
        {
            Car::create([
                'user_id'=>$user_id,
            ]);
            $city_text=CarText::where('city_id',$picked)->get();
            $text=$city_text[0]['text'];
            return response()->json([
                'status' => 'success',
                'isset' => 'не было, можно писать',
                'text'=>$text
            ], 200);
        }
    }

    public function get_vk_posts()
    {
        //нужно добавить конкретную технологию для старта в отдельную таблицу, чтобы каждый скрипт начинал со своей группы
        $final_result=[];
        $groups=VkSearchGroup::all();
        foreach ($groups as $group)
        {
            sleep(1);
            try {
                $xml = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-".$group['group_id']."&count=20&v=5.131&access_token=ebb4e240a677264ecf6de2ecfc9ab45a83a90c3773f309e92677ab19c624d132e9abe70e44d09a41cba59"));
            }
            catch (\Throwable $e)
            {
                return response()->json([
                    'status' => 'ошибка в получении списка постов группы',
                    'error'    =>  $e,
                    'group'    =>  $group_id,
                ], 200);
            }
            //  $xml->response->items=array_reverse($xml->response->items);


            $posts=[];
            foreach ($xml->response->items as $post)
            {
                // return $post->attachments[0]->type;
                $add_fl=1;
                //убираем пост со ссылкой или на статью
                if(isset($post->attachments))
                {
                    //убираем закреплённый пост
                    //убираем пост с посмотреть объявление
                    if((isset($post->is_pinned))||($post->attachments[0]->type=='link')||(isset($post->copy_history)))
                    {
                        $add_fl=0;
                    }
                }
                else
                {
                    $add_fl=0;
                }
                if($add_fl==1)
                {
                    array_unshift($posts, $post);
                }

            }

            $group_post_last=VkSearchGroup::where('group_id',$group['group_id'])->get('last_post');
            foreach ($posts as $post)
            {
                //закреплённый пост не берём и смотрим чтобы пост был новее чем тот что я уже брал
                if($post->id>$group_post_last[0]->last_post)
                {
                    array_push($final_result,$post);

//                VKPosts::where('group_id',$group_id)->
//                update([
//                    'last_post' => $post->id,
//                ]);
                }
            }
        }
        dd($final_result);
        return response()->json([
            'status' => 'собрано',
            'posts_list' =>$final_result,
        ], 200);
    }

}
