<?php

namespace App\Classes;

use Auth;
use DB;

class Producer
{
    public function store($prdc_id, $mood_json, $cate_json, $prdc_nick, $prdc_img_path, $prdc_audio_path, $prdc_bnk_accnt)
    {
        DB::insert("
        INSERT INTO producer (
            prdc_id,
            mood_json,
            cate_json,
            prdc_nick,
            prdc_img,
            prdc_sample,
            prdc_bnk_accnt,
            created_at,
            updated_at
        ) VALUES (
            :prdc_id,
            :mood_json,
            :cate_json,
            :prdc_nick,
            :prdc_img,
            :prdc_sample,
            :prdc_bnk_accnt,
            now(),
            now()
        )
        ", [
            'prdc_id' => $prdc_id,
            'mood_json' => $mood_json,
            'cate_json' => $cate_json,
            'prdc_nick' => $prdc_nick,
            'prdc_img' => $prdc_img_path,
            'prdc_sample' => $prdc_audio_path,
            'prdc_bnk_accnt' => $prdc_bnk_accnt
        ]);

        return true;
    }

    public function show($prdc_id)
    {
        $prdc_info = DB::select("
        SELECT
            b.*,
            p.mood_json,
            p.cate_json,
            p.created_at
        FROM bests b, producer p
        WHERE 1 = 1
            AND p.prdc_id = :prdc_id
            AND p.prdc_id = b.prdc_id
            AND p.state = 1
        LIMIT 1
        ", ['prdc_id' => $prdc_id]);

        return data_get($prdc_info, 0, null);
    }

    public function destroy($prdc_id)
    {
        DB::delete("
        DELETE FROM producer
        WHERE 1 = 1
            AND prdc_id = :prdc_id
        ", ['prdc_id' => $prdc_id]);

        return true;
    }

    public function by_user_id($user_id)
    {
        $prdc_info = DB::select("
        SELECT
            prdc_id,
            mood_json,
            cate_json,
            prdc_nick,
            prdc_img,
            state,
            prdc_sample,
            prdc_reject,
            created_at,
            updated_at
        FROM producer
        WHERE 1 = 1
            AND prdc_id = :user_id
        LIMIT 1
        ", ['user_id' => $user_id]);

        return data_get($prdc_info, 0, null);
    }

    public function by_prdc_nick($prdc_nick)
    {
        $prdc_info = DB::select("
        SELECT
            prdc_id,
            mood_json,
            cate_json,
            prdc_nick,
            prdc_img,
            state,
            prdc_sample,
            prdc_reject,
            created_at,
            updated_at
        FROM producer
        WHERE 1 = 1
            AND prdc_nick = :prdc_nick
        LIMIT 1
        ", ['prdc_nick' => $prdc_nick]);

        return data_get($prdc_info, 0, null);
    }

    public function best_ten()
    {
        $res = DB::select("
        SELECT
            b.prdc_rank,
            b.prdc_id,
            b.prdc_nick,
            b.prdc_img,
            b.prdc_like,
            b.prdc_follow,
            b.prdc_buy
        FROM bests b, producer p
        WHERE 1 = 1
            AND p.prdc_id = b.prdc_id
            AND p.state = 1
        ORDER BY b.prdc_rank DESC, b.prdc_id ASC
        LIMIT 10
        ");

        return $res;
    }
    public function orderby_prdc($orderby, $offset = null, $limit = null)
    {
        $order = '';
        if ($orderby == 'rank') {
            $order = ' ORDER BY prdc_rank DESC, prdc_id ASC ';
        } elseif ($orderby == 'like') {
            $order = ' ORDER BY prdc_like DESC, prdc_id ASC ';
        } elseif ($orderby == 'follow') {
            $order = ' ORDER BY prdc_follow DESC, prdc_id ASC ';
        } elseif ($orderby == 'desc') {
            $order = ' ORDER BY prdc_buy DESC, prdc_id ASC ';
        }
        $res = DB::select("
        SELECT
            b.prdc_id,
            b.prdc_rank,
            b.prdc_id,
            b.prdc_nick,
            b.prdc_img,
            b.prdc_like,
            b.prdc_follow,
            b.prdc_buy
        FROM bests b, producer p
        WHERE 1 = 1
            AND p.prdc_id = b.prdc_id
            AND p.state = 1
        ".$order."
        OFFSET COALESCE(:offset::int, 0) LIMIT COALESCE(:limit::int, 100)
        ", [
            'offset' => $offset,
            'limit' => $limit
        ]);

        return $res;
    }

    public function updateinfo($prdc_id, $request){
        $update = '';
        $param = array();
        $param['prdc_id'] = $prdc_id;
        $storage_image_save_path = config('filesystems.maker_thumb');
        if ($request->hasFile('prdc_img')) {
            if ($request->file('prdc_img')->isValid()) {
                $update .=', prdc_img = :prdc_img';
                $param['prdc_img'] = str_replace($storage_image_save_path.'/', "", $request->prdc_img->store($storage_image_save_path));
            }
        }
        if($request->has('prdc_nick')){
            $update .=', prdc_nick = :prdc_nick';
            $param['prdc_nick'] = $request->prdc_nick;
        }
        if($request->has('mood_json')){
            $update .=', mood_json = :mood_json';
            $param['mood_json'] = $request->mood_json;
        }
        if($request->has('cate_json')){
            $update .=', cate_json = :cate_json';
            $param['cate_json'] = $request->cate_json;
        }
        $res = DB::update("
        UPDATE
	producer
SET
    state = state"
    .$update.
    "
WHERE
	prdc_id = :prdc_id
        ", $param);

        return $res;
    }
}
