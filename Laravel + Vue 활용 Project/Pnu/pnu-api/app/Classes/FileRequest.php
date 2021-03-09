<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Storage;

class FileRequest
{
    public function set(Request $request, $key, $storage_save_path = null, $filename = null)
    {
        if ($request->hasFile($key)) {
            if ($request->file($key)->isValid()) {
                if (empty($storage_save_path)) {
                    $storage_save_path = Storage::path('');
                }

                if (!empty($filename)) {
                    $old_path = $storage_save_path.'/'.$filename;
                    if (Storage::exists($old_path)) {
                        Storage::delete($old_path);
                    }
                }

                $new_path = Storage::putFile($storage_save_path, $request->file($key), 'public');
                $filename = basename($new_path);
            }
        }

        return $filename;
    }
    public function remove($storage_save_path = null, $filename = null)
    {
        if (!empty($storage_save_path) && !empty($filename)) {
            $old_path = $storage_save_path.'/'.$filename;
            if (Storage::exists($old_path)) {
                Storage::delete($old_path);
            }
        }

        return true;
    }
}
