<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WallNotice;
use App\Models\WallNoticeLike;
use Illuminate\Http\Request;

class WallNoticeController extends Controller
{
    public function getAll()
    {
        $array = ['error' => '', 'list' => []];

        $user = \auth()->user();
        $wallNotices = WallNotice::all();

        foreach ($wallNotices as $key => $value) {
            $wallNotices[$key]['likes'] = 0;
            $wallNotices[$key]['liked'] = false;

            $likes = WallNoticeLike::where('wall_notice_id', $value->id)->count();
            $wallNotices[$key]['likes'] = $likes;

            $myLike = WallNoticeLike::where('wall_notice_id', $value->id)->where('user_id', $user->id)->count();

            if ($myLike > 0) {
                $wallNotices[$key]['liked'] = true;
            }
        }

        $array['list'] = $wallNotices;

        return $array;
    }

    public function like($id)
    {
        $array = ['error' => ''];
        $user = \auth()->user();

        $myLike = WallNoticeLike::where('wall_notice_id', $id)->where('user_id', $user->id)->count();

        if ($myLike > 0) {
            WallNoticeLike::where('wall_notice_id', $id)->where('user_id', $user->id)->delete();
            $array['liked'] = false;
        } else {

            $wallNotice = new WallNoticeLike();
            $wallNotice->wall_notice_id = $id;
            $wallNotice->user_id = $user->id;
            $wallNotice->save();

            $array['liked'] = true;
        }

        $array['likes'] = WallNoticeLike::where('wall_notice_id', $id)->count();

        return $array;
    }
}
