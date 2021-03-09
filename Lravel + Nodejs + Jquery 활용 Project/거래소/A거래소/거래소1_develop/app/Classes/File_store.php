<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as InterventionImage;

use DB;
use File;

class File_store {
	public function Image_store($store_path, $images){
        $storage_path = "../storage/app/public/image/";

        if(!File::exists($storage_path.$store_path)) {
            File::makeDirectory($storage_path.$store_path, $mode = 0777, true, true);
        }

        $store_path = $store_path.'/';

        $paths = array();

        if($images){
			$i = 0;
			foreach($images as $key => $image){
				if ($image->isValid()) {
                    $mime = $image->getMimeType();
                    $extension = $this->getImgExtension($mime);

                    if($extension != 'not_image')
                    {
                        // 이미지 회전 후 저장
                        $img = InterventionImage::make($image)->orientate();

                        if($img->width() >= 1000){
                            $img->resize(700, null, function ($constraint) {
                                $constraint->aspectRatio(); //비율유지
                            });
                        }else{
                            //$img->rotate($rotate)->encode('jpg');
                        }

                        $hash = md5($img->__toString(). time());
                        $paths[$i] = $storage_path.$store_path.$hash.$extension;
                        $img->save($paths[$i]);

                        $paths[$i] = '/'.str_replace($storage_path.$store_path,"",$paths[$i]);
                    }else{
                        return '이미지 파일만 업로드 가능합니다.';
                    }

					$i++;
				}else{
                    return '유효하지 않은 파일입니다.';
                }	
			}
        }else{
            return '파일이 존재하지 않습니다.';
        }

        return $paths;
    }

    public function ImageStoreWithName($store_path, $images){
        $storage_path = "../storage/app/public/image/";

        if(!File::exists($storage_path.$store_path)) {
            File::makeDirectory($storage_path.$store_path, $mode = 0777, true, true);
        }

        $store_path = $store_path.'/';

        $paths = array();

        if($images){
            $i = 0;
            //dd($images);
			foreach($images as $key => $image){
				if ($image->isValid()) {
                    //dd('test');
                    $mime = $image->getMimeType();
                    $extension = $this->getImgExtension($mime);

                    if($extension != 'not_image')
                    {
                        $filename = $image->getClientOriginalName();
                        // 이미지 회전 후 저장
                        $img = InterventionImage::make($image)->orientate();

                        if($img->width() >= 1000){
                            $img->resize(700, null, function ($constraint) {
                                $constraint->aspectRatio(); //비율유지
                            });
                        }else{
                            //$img->rotate($rotate)->encode('jpg');
                        }

                        $hash = md5($img->__toString(). time());
                        $paths[$i] = $storage_path.$store_path.$filename;
                        if(File::exists($paths[$i])) {
                            File::delete($paths[$i]);
                        }
                        $img->save($paths[$i]);

                        $paths[$i] = '/'.str_replace($storage_path.$store_path,"",$paths[$i]);
                    }else{
                        return '이미지 파일만 업로드 가능합니다.';
                    }

					$i++;
				}else{
                    return '유효하지 않은 파일입니다.';
                }	
			}
        }else{
            return '파일이 존재하지 않습니다.';
        }

        return $paths;
    }

    public function File_store($store_path, $files){
        $storage_path = "../storage/app/public/file/";

        $store_path = $store_path.'/';

        $paths = array();
 
        if($files){
			$i = 0;
			foreach($files as $key => $file){
				if ($file->isValid()) {
                    $mime = $file->getMimeType();
                    $extension = $this->getFileExtension($mime);

                    if($extension != 'not_file')
                    {
                        if($file->getSize() < 41943040){
                            $paths[$i] = $file->storeAs($storage_path.$store_path, time().'[['.$file->getClientOriginalName());
                            $paths[$i] = '/'.str_replace($storage_path.$store_path,"",$paths[$i]);
                        }else{
                            return '업로드 가능한 최대 용량을 초과하였습니다.';
                        }
                        
                    }else{
                        return 'pdf doc xls ppt hwp 파일만 업로드 가능합니다.';
                    }

					$i++;
				}else{
                    return '유효하지 않은 파일입니다.';
                }	
			}
        }else{
            return '파일이 존재하지 않습니다.';
        }

        return $paths;
    }

    public function getImages($contents){
        // 정규식을 이용해서 img 태그 전체 / src 값만 추출하기
        preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contents, $matches);
        
        // 이미지 태그 src 값 중에서 "image" 문자열 이하 값 알아내기
        $ary_rtn = array();

        foreach($matches[1] as $k => $v) {
            $t = explode("image", $v);
            array_push($ary_rtn, $t[1]);
        }

        return $ary_rtn;
    }

    public function imageUpdate($origin_images, $new_images){
        $default_path = "../storage/app/public/image/";
        
        $exist = false;

        foreach($origin_images as $origin_image){
            foreach($new_images as $new_image){
                if($origin_image === $new_image){
                    $exist = true;
                }
            }

            if(!$exist){
                $img_path = $default_path.$origin_image;
                if(File::exists($img_path)) {
                    File::delete($img_path);
                }
            }

            $exist = false;
        }
    }

    private function getImgExtension($mime){
        switch($mime){
            case 'image/png':
                return '.png';
                break;
            case 'image/jpeg':
                return '.jpg';
                break;
            case 'image/gif':
                return '.gif';
                break;
            case 'image/bmp':
                return '.bmp';
                break;
            case 'image/svg':
                return '.svg';
                break;
            default :
                return 'not_image';
                break;
        }
    }

    private function getFileExtension($mime){
        switch($mime){
            case 'application/msword':
                return '.doc';
                break;
            case 'application/vnd.ms-powerpoint':
                return '.ppt';
                break;
            case 'application/pdf':
                return '.pdf';
                break;
            case 'application/vnd.ms-excel':
                return '.xls';
                break;
            case 'application/x-hwp':
                return '.hwp';
                break;
            case 'application/haansofthwp':
                return '.hwp';
                break;
            case 'application/vnd.hancom.hwp':
                return '.hwp';
                break;
            case 'application/zip':
                return '.zip';
                break;
            case 'application/x-7z-compressed':
                return '.7z';
                break;
            default :
                return 'not_file';
                break;
        }
    }
}
