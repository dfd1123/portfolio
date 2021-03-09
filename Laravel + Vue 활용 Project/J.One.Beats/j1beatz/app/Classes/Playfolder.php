<?php

namespace App\Classes;

use Auth;
use DB;

class Playfolder
{
    public function index()
    {
        $user = auth('api')->user();
        $user_id = empty($user) ? null : $user->user_id;
        $res = DB::select("
        WITH beat_jsons AS (
            SELECT
                pf_id,
                JSONB_ARRAY_ELEMENTS_TEXT(beat_json)::INT AS beat_id
            FROM playfolder
        )
        SELECT
            pf.pf_id,
            pf.pf_title,
            pf.created_at,
            COUNT(p.prdc_id) AS pf_qtt
        FROM playfolder pf
        LEFT JOIN beat_jsons j ON j.pf_id = pf.pf_id
        LEFT JOIN beat b 
            ON b.beat_id = j.beat_id
            AND b.state = 1 
        LEFT JOIN producer p 
            ON p.prdc_id = b.prdc_id 
            AND p.state = 1
        WHERE user_id = :user_id
        GROUP BY pf.pf_id
        ORDER BY pf.pf_id DESC
        ",[
            'user_id' => $user_id
        ]);

        return $res;
    }
    public function newfolder(){
        $user_id = auth('api')->user()->user_id;
        DB::insert("
        INSERT INTO playfolder (
            user_id,
            pf_title
        ) VALUES (
            :user_id,
            ''
        )",[
            'user_id' => $user_id
        ]);

        return true;
    }
    public function PlayfolderDelete($user_id, $id){
        $res = DB::delete("
        DELETE FROM playfolder
        WHERE pf_id = :pf_id
        AND user_id = :user_id
        ",[
            'pf_id' => $id,
            'user_id' => $user_id
        ]);
        return true;
    }
    public function PlayfolderBeatDelete($beats, $id){
        $user_id = auth('api')->user()->user_id;
        $beat_json = array();
        foreach($beats as $beat){
            array_push($beat_json,$beat);
        }
        DB::update("
        UPDATE playfolder SET
            beat_json = (
                SELECT JSONB_ARRAY_ELEMENTS_TEXT(
                    JSONB_BUILD_ARRAY(
                        ARRAY(
                            (
                                SELECT JSONB_ARRAY_ELEMENTS_TEXT(beat_json)::INT AS beat
                                FROM playfolder
                                WHERE pf_id = :pf_id
                                AND user_id = :user_id
                            )
                            EXCEPT
                            (
                                SELECT JSON_ARRAY_ELEMENTS_TEXT(:beat_json)::INT
                            )
                        )
                    )
                )::JSONB AS beats
            )
        WHERE user_id = :user_id
        AND pf_id = :pf_id
        ",[
            'beat_json' => json_encode($beat_json),
            'user_id' => $user_id,
            'pf_id' => $id
        ]);
        return true;
    }
    public function detail($id){
        $user_id = auth('api')->user()->user_id;
        $res = DB::select("
        SELECT
            pf_id,
            pf_title,
            beat_json,
            created_at
        FROM
            playfolder
        WHERE user_id = :user_id
            AND pf_id = :pf_id
        ",[
            'user_id' => $user_id,
            'pf_id' => $id
        ]);

        return $res;
    }
    public function titleupdate($req, $id){
        if($req->has('pf_title')){
            $pf_title = $req->pf_title;
        }else{
            return false;
        }
        $user_id = auth('api')->user()->user_id;
        DB::update("
        UPDATE playfolder SET
            pf_title = :pf_title
        WHERE user_id = :user_id
            AND pf_id = :pf_id
        ",[
            'pf_title' => $pf_title,
            'user_id' => $user_id,
            'pf_id' => $id
        ]);
        return true;
    }
    public function currentfolderaddbeat($beats, $id){
        $user_id = auth('api')->user()->user_id;
        $beat_json = array();
        foreach($beats as $beat){
            array_push($beat_json,$beat);
        }
        DB::update("
        UPDATE playfolder SET
            beat_json = beat_json || (
                SELECT JSONB_ARRAY_ELEMENTS_TEXT(
                    JSONB_BUILD_ARRAY(
                        ARRAY(
                            (
                                SELECT
                                JSON_ARRAY_ELEMENTS_TEXT(:beat_json)::INT
                            )
                            EXCEPT
                            (
                                SELECT JSONB_ARRAY_ELEMENTS_TEXT(beat_json)::INT AS beat
                                FROM playfolder
                                WHERE pf_id = :pf_id
                                AND user_id = :user_id
                            )
                        )
                    )
                )::JSONB AS beats
            )
        WHERE
            user_id = :user_id
            AND pf_id = :pf_id
        ",[
            'pf_id' => $id,
            'beat_json' => json_encode($beat_json),
            'user_id' =>$user_id
        ]);
        return true;
    }
    public function newfolderaddbeat($pf_title, $beats){
        $user_id = auth('api')->user()->user_id;

        $beat_json = array();
        foreach($beats as $beat){
            array_push($beat_json,$beat);
        }
        DB::insert("
        INSERT INTO playfolder (
            user_id,
            pf_title,
            beat_json
        ) VALUES (
            :user_id,
            :pf_title,
            :beat_json
        )
        ",[
            'pf_title' => $pf_title,
            'beat_json' => json_encode($beat_json),
            'user_id' =>$user_id
        ]);
        return true;
    }
}
