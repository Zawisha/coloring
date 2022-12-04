<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\LikeCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function setLike(Request $request)
    {
        $type_of_like =  $request->input('type_of_like');
        $colored_id =  $request->input('colored_id');
        $type_of_content =  $request->input('type_of_content');

        if(Auth::id())
        {
            $userId = Auth::id();

            //если нет лайка на этом посте от этого пользователя
            $list_like = Like::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->where('user_id', $userId)->get('type_of_like');
            if($list_like->isEmpty())
            {
                     Like::create([
                    'type_of_like'=>$type_of_like,
                    'user_id' => $userId,
                    'type_of_content' => $type_of_content,
                    'post_id' => $colored_id
                ]);

                //если было пусто вообще на этом посте вообще никто лайк не ставил
                $list_like_count = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->get('count_of_like');
                if($list_like_count->isEmpty())
                {
                    if ($type_of_like=='1')
                    {
                        LikeCounter::create([
                            'count_of_like'=>'1',
                            'type_of_content' => $type_of_content,
                            'post_id' => $colored_id
                        ]);
                    }
                }
                //если не было пусто вообще, но не было лайка от текущего пользователя
                else {
                    if ($type_of_like=='1')
                    {
                        $list_like_count=$list_like_count['0']['count_of_like']+1;
                    $list_like = LikeCounter::where('post_id', $colored_id)->where('type_of_content', '1')->update(
                        [
                            'count_of_like' => $list_like_count
                        ]
                    );
                    }
                }
            }
            //если лайк был, но не известно лайк или дизлайк
            else
            {
                if($list_like['0']['type_of_like']==$type_of_like)
                {

                    $list_like = Like::where('post_id', $colored_id)->where('user_id', $userId)->where('type_of_content', $type_of_content)->delete();
                    if ($type_of_like=='1')
                    {
                    $list_like_count = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->get('count_of_like');
                    $list_like_count= $list_like_count['0']['count_of_like']-1;
                    $list_like = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->update(
                        [
                            'count_of_like' => $list_like_count
                        ]
                    );
                    }
                }
                else
                {
                    $list_like = Like::where('post_id', $colored_id)->where('user_id', $userId)->where('type_of_content', $type_of_content)->update(
                        [
                           // 'type_of_content' => $type_of_like
                            'type_of_content' => $type_of_content
                        ]
                    );
                    if ($type_of_like=='1')
                    {
                    $list_like_count = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->get('count_of_like');
                        $list_like_count=  $list_like_count['0']['count_of_like']+1;
                    $list_like = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->update(
                        [
                            'count_of_like' => $list_like_count
                        ]
                    );
                    }
                    else
                    {
                        $list_like_count = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->get('count_of_like');
                        $list_like_count= $list_like_count['0']['count_of_like']-1;
                        $list_like = LikeCounter::where('post_id', $colored_id)->where('type_of_content', $type_of_content)->update(
                            [
                                'count_of_like' => $list_like_count
                            ]
                        );
                    }
                }
            }

        }
    }
}
