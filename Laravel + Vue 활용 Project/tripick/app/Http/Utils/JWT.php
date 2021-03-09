<?php

namespace App\Http\Utils;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use App\Http\Utils\Encryptor;

class JWT
{
    public static $instance =null;

    public function iss_tkn($user_id)
    {
        $signer = new Sha256();
        $time = time();

        $accessTkn  = 'no-auth';
        $refreshTkn = 'no-auth';
        try {
            $accessTkn  = $this->iss_a_tkn($time, $signer, $user_id);
            $refreshTkn = $this->iss_r_tkn($time, $signer, $user_id);
        } catch (exception $e) {
            return null;
        }

        $res = array(
         'access_token'=> Encryptor::get_instance()->aes256_encryption($accessTkn, config('constant.AES_SECRET_KEY'))
        ,'refresh_token'=> Encryptor::get_instance()->aes256_encryption($refreshTkn, config('constant.AES_SECRET_KEY')) );
       
        return  $res;
    }
    private function iss_a_tkn($time, $signer, $user_id){
        $accessTkn = (new Builder())->issuedBy(env('APP_URL'))
        ->permittedFor(env('APP_URL'))
        ->identifiedBy(mt_rand().'A', true)  //토큰고유식별번호
        ->issuedAt($time)            //iss시간 대응
        ->canOnlyBeUsedAfter($time)  //nbf에 대응
        ->expiresAt($time + 3600)   //15분
        ->withClaim('uid', $user_id)   //최고관리자, -10
        ->getToken($signer, new Key(config('constant.JWT_SECRET_A_KEY'))); 
        return $accessTkn;
    }
    private function iss_r_tkn($time, $signer, $user_id){
        $refreshTkn = (new Builder())->issuedBy(env('APP_URL'))
        ->permittedFor(env('APP_URL'))
        ->identifiedBy(mt_rand().'R', true)  
        ->issuedAt($time)           
        ->canOnlyBeUsedAfter($time+900) 
        ->expiresAt($time + 3600*6)  //6시간
        ->withClaim('uid', $user_id)  
        ->withClaim('ref', 'ref')  
        ->getToken($signer, new Key(config('constant.JWT_SECRET_R_KEY'))); 
        return $refreshTkn;
    }

    public function refresh_tkn($request){

        $rtoken = $request->cookie('Refresh');
        if ($request->headers->has('Refresh')) {
            $rtoken = $request->header('Refresh');
        }

        if( empty($rtoken) || strlen($rtoken) < 20 ){
            return null;
        }

        $rtkn_res = $this->decode_tkn($rtoken, config('constant.AES_SECRET_KEY'));

        //Refresh token 오류
        if($rtkn_res ===null){
            $res = array('state'=>config('rescode.NO_AUTH_100')
            ,'uid' =>null
            ,'msg'=>'rtoken not available3');
            return null;
        }

        $rtkn = $this->iss_a_tkn(time(), new Sha256());
        
        return  Encryptor::get_instance()->aes256_encryption($rtkn, config('constant.AES_SECRET_KEY'));
    }

    public function decode_tkn($token, $secret)
    {
        $res = array('state'=>config('rescode.NO_AUTH_100')
                    ,'uid' =>null
                    ,'msg'=>'JWT No token');
        
        $token =  urldecode($token);

        $token = Encryptor::get_instance()->aes256_decryption($token, config('constant.AES_SECRET_KEY'));
            
        try {

            $signer = new Sha256();
            $token = (new Parser())->parse($token); // Parses from a string

            if ($token->verify($signer, $secret)) {

                $res['uid']= $token->getClaim('uid');

                if($token->isExpired()){
                    $res = array('state'=>config('rescode.AUTH_EXPIRED_101')
                    ,'uid' =>null 
                    ,'msg'=>'token expired');
                    return $res;
                }

                $res['msg']='has token';
            }
        } catch (\Throwable  $e) {
            $res = array('state'=>config('rescode.NO_AUTH_100')
            ,'uid' =>null , 'msg'=>'token not available-2');
        }

        return $res;
    }

    public function refresh_tkn_middleware($r_token, $user_id){
        if( empty($r_token) || strlen($r_token) < 20 ){
            return null;
        }

        $rtkn_res = $this->decode_tkn($r_token, config('constant.AES_SECRET_KEY'));

        //Refresh token 오류
        if($rtkn_res ===null){
            $res = array('state'=>config('rescode.NO_AUTH_100')
            ,'uid' =>null
            ,'msg'=>'rtoken not available3');
            return null;
        }

        $rtkn = $this->iss_a_tkn(time(), new Sha256(), $user_id);
        
        return  Encryptor::get_instance()->aes256_encryption($rtkn, config('constant.AES_SECRET_KEY'));
    }

    public static function get_instance()
    {
        return new JWT();
    }
}
