<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as InterventionImage;

use DB;
use File;
use Storage;

class File_store
{
  public function Image_store($store_path, $images)
  {
    $storage_path = "image/";

    if (!File::exists("../public/" . $storage_path . $store_path)) {
      File::makeDirectory($storage_path . $store_path, $mode = 0775, true, true);
    }

    $store_path = $store_path . '/';

    $paths = array();

    if ($images) {
      $i = 0;
      $randomNum = mt_rand(1, 1000000);
      foreach ($images as $key => $image) {
        //dd('2');
        if ($image->isValid()) {
          //dd('3');
          $mime = $image->getMimeType();
          $extension = $this->getImgExtension($mime);

          if ($extension != 'not_image') {
            // 이미지 회전 후 저장
            $img = InterventionImage::make($image)->orientate();

            if ($img->width() > 1600) {
              $img->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio(); //비율유지
              });
            } else {
              //$img->rotate($rotate)->encode('jpg');
            }

            $hash = md5($img->__toString() . time());
            $paths[$i] = $storage_path . $store_path . $hash . $key . $randomNum . $extension;
            $img->save("../public/" . $paths[$i]);
            //$img->save($paths[$i]);
          } else {
            //dd('4');
            return 'EXT_ERR';
          }

          $i++;
        } else {
          return 'VALID_ERR';
        }
      }
    } else {
      return 'PARAM_ERR';
    }
    return $paths;
  }

  public function Image_update($store_path, $images, $before_arr, $req_index)
  {
    $storage_path = "image/";
    $delete_path = "image/";

    if (!File::exists("../storage/app/public/" . $storage_path . $store_path)) {
      File::makeDirectory($storage_path . $store_path, $mode = 0775, true, true);
    }

    $store_path = $store_path . "/";

    $paths = array();

    if ($images) {
      $i = 0;
      foreach ($images as $key => $image) {
        if ($image->isValid()) {
          $mime = $image->getMimeType();
          $extension = $this->getImgExtension($mime);

          if ($extension != 'not_image') {
            // 이미지 회전 후 저장
            $img = InterventionImage::make($image)->orientate();

            if ($img->width() > 1200) {
              $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio(); //비율유지
              });
            } else {
              //$img->rotate($rotate)->encode('jpg');
            }

            $hash = md5($img->__toString() . time());
            $paths[$i] = $storage_path . $store_path . $hash . $key . $extension;
            $img = $img->stream(); //공용저장을 위해 이렇게해야만함
            Storage::put($paths[$i], $img->__toString()); //공용저장을 위해 이렇게해야만함
            //$img->save($paths[$i]);


            $req_index[] = $paths[$i];

            /*if(isset($req_index[$key])){
                            $index = $req_index[$key]; //바꿀 값들 파일 위치 index값
                        }else{
                            $index = count($before_arr);
                        }
                        //$arr = array(); //배열교체를 위한 대체배열 선언
                        //$arr[] = $paths[$i]; //배열교체 값 삽입
                        
                        if(isset($before_arr[$index])){
                        	if(Storage::has($before_arr[$index])) {
                                Storage::delete($before_arr[$index]);
                            }
                        }
                        unset($before_arr[$index]);
                        $before_arr[] = $paths[$i];*/
            //array_splice($before_arr,$index,1,$arr); //해당배열 교체


          } else {
            return 'EXT_ERR';
          }

          $i++;
        } else {
          return 'VALID_ERR';
        }
      }

      if ($before_arr !== $req_index && $before_arr !== null) {
        foreach ($before_arr as $row) {
          info(array_search($row, $req_index));
          info($row);
          info($req_index);
          if (array_search($row, $req_index) === false) {

            if (Storage::has($row)) {
              Storage::delete($row);
            }
          }
        }
      }
    } else {
      return 'PARAM_ERR';
    }

    return $req_index;
  }

  /*public function ImageStoreWithName($store_path, $images){
        if (App::environment(['local'])) {
            $storage_path = "public/image/";
        }else{
            $storage_path = "public/efs/image/";
        }

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
    }*/

  public function File_store($store_path, $files)
  {
    $storage_path = "file/";

    $store_path = $store_path;

    $paths = array();

    if ($files) {
      $i = 0;
      foreach ($files as $key => $file) {
        if ($file->isValid()) {
          $mime = $file->getMimeType();
          $extension = $this->getFileExtension($mime);

          if ($extension != 'not_file') {
            if ($file->getSize() < 41943040) {
              $paths[$i] = $file->storeAs($storage_path . $store_path, time() . '[' . $file->getClientOriginalName());
            } else {
              return 'SIZE_ERR';
            }
          } else {
            return 'EXT_ERR';
          }

          $i++;
        } else {
          return 'VALID_ERR';
        }
      }
    } else {
      return 'PARAM_ERR';
    }

    return $paths;
  }

  public function File_update($store_path, $files, $before_arr, $req_index)
  {
    $storage_path = "file/";
    $delete_path = "file/";

    $store_path = $store_path . "/";

    $paths = array();

    if ($files) {
      $i = 0;
      foreach ($files as $key => $file) {
        if ($file->isValid()) {
          $mime = $file->getMimeType();
          $extension = $this->getFileExtension($mime);

          if ($extension != 'not_file') {
            if ($file->getSize() < 41943040) {
              $paths[$i] = $file->storeAs($storage_path . $store_path, time() . $file->getClientOriginalName());
              if (isset($req_index[$key])) {
                $index = $req_index[$key]; //바꿀 값들 파일 위치 index값
              } else {
                $index = count($before_arr);
              }
              $arr = array(); //배열교체를 위한 대체배열 선언
              $arr[] = $paths[$i]; //배열교체 값 삽입

              if (isset($before_arr[$index])) {

                if (Storage::has($before_arr[$index])) {
                  Storage::delete($before_arr[$index]);
                }
              }
              array_splice($before_arr, $index, 1, $arr); //해당배열 교체

            } else {
              return 'SIZE_ERR';
            }
          } else {
            return 'EXT_ERR';
          }
          $i++;
        } else {
          return 'VALID_ERR';
        }
      }
    } else {
      return 'PARAM_ERR';
    }
    return $before_arr;
  }

  public function getImages($contents)
  {
    // 정규식을 이용해서 img 태그 전체 / src 값만 추출하기
    preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $contents, $matches);

    // 이미지 태그 src 값 중에서 "image" 문자열 이하 값 알아내기
    $ary_rtn = array();

    foreach ($matches[1] as $k => $v) {
      $t = explode("image", $v);
      array_push($ary_rtn, $t[1]);
    }

    return $ary_rtn;
  }

  public function imageUpdate($origin_images, $new_images)
  {
    if (App::environment(['local'])) {
      $default_path = "../storage/app/public/image/";
    } else {
      $default_path = "../storage/app/public/efs/image/";
    }

    $exist = false;

    foreach ($origin_images as $origin_image) {
      foreach ($new_images as $new_image) {
        if ($origin_image === $new_image) {
          $exist = true;
        }
      }

      if (!$exist) {
        $img_path = $default_path . $origin_image;
        if (File::exists($img_path)) {
          File::delete($img_path);
        }
      }

      $exist = false;
    }
  }

  private function getImgExtension($mime)
  {
    switch ($mime) {
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
      default:
        return 'not_image';
        break;
    }
  }

  private function getFileExtension($mime)
  {
    switch ($mime) {
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
      default:
        return 'not_file';
        break;
    }
  }
}