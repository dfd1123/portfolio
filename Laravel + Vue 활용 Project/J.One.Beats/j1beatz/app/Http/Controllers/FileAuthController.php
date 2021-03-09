<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Facades\App\Classes\BeatRequestHistory;
use Input;
use DB;
use Storage;

class FileAuthController extends Controller
{
    public function streaming($filename)
    {
        if (!$filename) {
            return abort(404);
        }

        $id = explode('.', $filename, 2)[0];

        $res = DB::select("
        SELECT
            b.beat_path->>'mp3' path
        FROM audio_verify_temp a, beat b
        WHERE 1 = 1
            AND a.id = :id
            AND a.beat_id = b.beat_id
            AND expires_at >= now()
        LIMIT 1
        ", [
            'id' => $id
        ]);

        if (empty($res)) {
            return abort(403);
        }

        $path = data_get($res[0], 'path', null);
        if ($path === null) {
            return abort(404);
        }

        try {
            return response()->file(storage_path() . '/app/file/' . config('filesystems.beat_wav') . $path);
        } catch (FileNotFoundException $e) {
            return abort(404);
        }
    }

    public function download($extension, $po_id)
    {
        if (!$extension || !$po_id) {
            return abort(404);
        }

        $download_info = DB::select("
        SELECT
            b.beat_id,
            bo.po_id,
            b.beat_path->>'mp3' mp3,
            b.beat_path->>'wav' wav
        FROM beat_order bo, beat b
        WHERE 1 = 1
            AND bo.user_id = :user_id
            AND bo.po_id = :po_id
            AND bo.beat_id = b.beat_id
            AND bo.created_at + INTERVAL '7' DAY >= now()
            AND bo.state = 2
        LIMIT 1
        ", [
            'user_id' => auth()->user()->user_id,
            'po_id' => $po_id,
        ]);
        $download_info = data_get($download_info, 0, null);

        if ($download_info === null) {
            return abort(403);
        }

        try {
            BeatRequestHistory::store(1, auth()->user()->user_id, $download_info->beat_id, null, $download_info->po_id);
            return response()->file(storage_path() . '/app/file/' . config("filesystems.beat_wav") . $download_info->{$extension});
        } catch (FileNotFoundException $e) {
            return abort(404);
        }
    }

    public function free($beat_id)
    {
        if (!$beat_id) {
            return abort(404);
        }

        $res = DB::select("
        WITH los AS
        (
            SELECT
                lo.user_id,
                lo.lo_id
            FROM license_order lo, license l
            WHERE 1 = 1
                AND lo.lcens_id = l.lcens_id
                AND lo.user_id = :user_id
                AND lo.end_at >= now()
                AND l.lcens_type = 1
                AND lo.state = 2
                AND l.state = 1
            LIMIT 1
        )
        SELECT
            beat_path->>'mp3' mp3,
            beat_path->>'clip' clip,
            (SELECT lo_id FROM los) lo_id
        FROM beat
        WHERE 1 = 1
            AND beat_id = :beat_id::int
            AND beat_free = 1
        LIMIT 1
        ", [
            'user_id' => auth()->user()->user_id,
            'beat_id' => $beat_id
        ]);
        $free_info = data_get($res, 0, null);

        if ($free_info === null) {
            return abort(404);
        }

        // 스트리밍 이용권 체크
        if ($free_info->lo_id !== null) {
            BeatRequestHistory::store(2, auth()->user()->user_id, $beat_id, $free_info->lo_id, null);
        } else {
            BeatRequestHistory::store(3, auth()->user()->user_id, $beat_id, null, null);
        }
        
        // 무료 다운로드 시 보이스태그 있는 mp3 풀버전 일괄 적용 (wav 디렉토리에 보이스태그 mp3 파일 같이 저장됨))
        $file_path = storage_path() . '/app/file/' . config('filesystems.beat_wav') . $free_info->mp3;

        try {
            return response()->file($file_path);
        } catch (FileNotFoundException $e) {
            return abort(404);
        }
    }
}
