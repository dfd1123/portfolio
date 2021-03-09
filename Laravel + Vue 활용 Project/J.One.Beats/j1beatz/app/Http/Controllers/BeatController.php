<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Input;
use FFMpeg;
use Storage;
use \Facades\App\Classes\Beat;
use \Facades\App\Classes\Producer;
use \Facades\App\Classes\Mp3file;

class BeatController extends Controller
{
    public function __construct()
    {
        $this->middleware([/*'passcookie', */'auth:api'])->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $req = Input::get('req');
        switch ($req) {
            case 'realtime_top':

            $params = [
                'mood_id' => Input::get('mood_id', null),
                'cate_id' => Input::get('cate_id', null),
                'offset' => Input::get('offset', null),
                'limit' => Input::get('limit', null)
            ];
            $res = Beat::realtime_top($params);
            return response()->json($res);

            case 'realtime_latest':

            $params = [
                'mood_id' => Input::get('mood_id', null),
                'cate_id' => Input::get('cate_id', null),
                'offset' => Input::get('offset', null),
                'limit' => Input::get('limit', null)
            ];
            $res = Beat::realtime_latest($params);
            return response()->json($res);

            case 'by_mood_top_50':

            $mood1 = Input::get('mood1');
            $mood2 = Input::get('mood2');
            $mood3 = Input::get('mood3');
            $res = Beat::by_mood_top_50($mood1, $mood2, $mood3);
            return response()->json($res);

            case 'prdc_list':

            $prdc_id = Input::get('prdc_id');
            $offset = Input::get('offset', 0);
            $limit = Input::get('limit', 100);
            $res = Beat::prdc_list($prdc_id, $offset, $limit);
            return response()->json($res);

            case 'prdc_detail':

            $prdc_id = Input::get('prdc_id');
            $beat_id = Input::get('beat_id');
            $res = Beat::prdc_detail($prdc_id, $beat_id);
            return response()->json($res);

            case 'by_prdc_id':

            $prdc_id = Input::get('prdc_id');
            $res = Beat::by_prdc_id($prdc_id);
            return response()->json($res);

            case 'by_prdc_id_top_10':

            $prdc_id = Input::get('prdc_id');
            $res = Beat::by_prdc_id_top_10($prdc_id);
            return response()->json($res);

            case 'by_mood_id_top_10':

            $mood_id = Input::get('mood_id');
            $res = Beat::by_mood_id_top_10($mood_id);
            return response()->json($res);

            case 'list_by_keyword':

            $keyword = trim(Input::get('keyword', ""));
            $offset = Input::get('offset', null);
            $limit = Input::get('limit', null);

            if ($keyword === "") {
                return response()->json(null, 422);
            }

            $tag_count = substr_count($keyword, '#');
            if ($tag_count > 1) {
                return response()->json(null, 422);
            }

            if ($tag_count === 1) {
                if (mb_strlen($keyword, 'utf-8') < 2) {
                    return response()->json(null, 422);
                }

                // # 제거
                $keyword = str_replace('#', '', $keyword);

                $params = [
                    'tag' => $keyword,
                    'offset' => $offset,
                    'limit' => $limit
                ];
                $res = Beat::list_by_keyword($params);
                return response()->json($res, 200);
            }

            if ($tag_count === 0) {
                if (mb_strlen($keyword, 'utf-8') < 1) {
                    return response()->json(null, 422);
                }

                $params = [
                    'word' => $keyword,
                    'offset' => $offset,
                    'limit' => $limit
                ];
                $res = Beat::list_by_keyword($params);
                return response()->json($res, 200);
            }

            return response()->json(null, 422);

            case 'by_user_folder':

            $pf_id = Input::get('pf_id');
            $res = Beat::by_user_folder($pf_id);
            return response()->json($res);
        }

        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producer = Producer::by_user_id(auth()->user()->user_id);
        if ($producer === null) {
            return response()->json(null, 422);
        }

        $storage_image_save_path = config('filesystems.beat_thumb');
        $storage_audio_save_path = config('filesystems.beat_wav');
        $salt = $producer->prdc_id . date('Y-m-d H:i:s');

        $path1 = null; // image
        $path2 = null; // audio
        $wav = null;
        $mp3 = null;
        $clip = null;

        // 썸네일 image
        if ($request->hasFile('file1')) {
            if ($request->file('file1')->isValid()) {
                $md5_name = sha1($salt . md5_file($request->file('file1')->getRealPath()));
                $extension = $request->file('file1')->getClientOriginalExtension();
                $path1 = str_replace($storage_image_save_path.'/', "", $request->file1->storeAs($storage_image_save_path, $md5_name . "." . $extension));
            }
        }

        // 원본 wav
        if ($request->hasFile('file2')) {
            if ($request->file('file2')->isValid()) {
                $md5_name = sha1($salt . md5_file($request->file('file2')->getRealPath()));
                $extension = $request->file('file2')->getClientOriginalExtension();
                if ($extension == 'wav') {
                    $wav = str_replace($storage_audio_save_path.'/', "", $request->file2->storeAs($storage_audio_save_path, $md5_name . "." . $extension, 'file'));
                } else {
                    return response()->json(null, 422);
                }
            }
        } else {
            return response()->json(null, 422);
        }

        // 보이스태그 mp3
        if ($request->hasFile('file3')) {
            if ($request->file('file3')->isValid()) {
                $md5_name = sha1($salt . md5_file($request->file('file3')->getRealPath()));
                $extension = $request->file('file3')->getClientOriginalExtension();
                if ($extension == 'mp3') {
                    $clip_mp3 = str_replace($storage_audio_save_path.'/', "", $request->file3->storeAs($storage_audio_save_path, $md5_name . "." . $extension, 'file'));
                } else {
                    return response()->json(null, 422);
                }
            }
        } else {
            return response()->json(null, 422);
        }

        // 원본 wav -> 원본 mp3
        FFMpeg::fromDisk('file')
            ->open(config('filesystems.beat_wav') . $wav)
            ->export()
            ->toDisk('file')
            ->inFormat(new \FFMpeg\Format\Audio\Mp3)
            ->save(config('filesystems.beat_mp3') . $md5_name . '.mp3');
        $mp3 = $md5_name . '.mp3';

        // 보이스태그 mp3 -> 1분짜리 보이스태그 mp3
        $start = \FFMpeg\Coordinate\TimeCode::fromSeconds(0);
        $duration = \FFMpeg\Coordinate\TimeCode::fromSeconds(60);
        $audioClipFilter = new \FFMpeg\Filters\Audio\AudioClipFilter($start, $duration);
        $media2 = FFMpeg::fromDisk('file')
            ->open(config('filesystems.beat_wav') . $clip_mp3)
            ->addFilter($audioClipFilter)
            ->export()
            ->toDisk('shared')
            ->inFormat(new \FFMpeg\Format\Audio\Mp3)
            ->save(config('filesystems.beat_clip') . $md5_name . '_clip.mp3');
        $clip = $md5_name . '_clip.mp3';

        // 원본 mp3 곡 길이
        $duration = Mp3file::getDuration(false, Storage::disk('file')->path(config('filesystems.beat_mp3')). $md5_name . '.mp3');

        $path2 = json_encode(['mp3' => $mp3, 'wav' => $wav, 'clip' => $clip]);

        Beat::store(
            $producer,
            $request->mood_id,
            $request->cate_id,
            $request->beat_title,
            $request->beat_price,
            $request->beat_tag,
            $path1,
            $path2,
            $duration
        );

        return response()->json(null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $beat_info = Beat::show($id);
        if ($beat_info === null) {
            return response()->json(null, 404);
        }

        return response()->json($beat_info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $req = $request->req;
        switch ($req) {
            case 'beatdelete':
                $params = array();
                // 값 검증
                if ($request->has('beat_id')) {
                    $beat_id_len = strlen($request->beat_id);
                    if ($beat_id_len < 0) {
                        return response()->json([
                            'code' => '-1',
                            'message' => 'invalid beat_id'
                        ], 422);
                    }
                }
                Beat::toDeleteState(auth()->user()->user_id, $id);
            break;
            case 'beatfree':
            if (!$request->has('beat_free')) {
                return response()->json(null, 422);
            }
            $beat_free = $request->beat_free==0 ? 1 : 0;
            Beat::beatfree(auth()->user()->user_id, $id, $beat_free);
            break;
        }

        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
