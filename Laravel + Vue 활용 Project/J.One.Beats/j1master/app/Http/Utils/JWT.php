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

    public function iss_tkn()
    {
        $signer = new Sha256();
        $time = time();

        $token = 'no-auth';
        try {
            $token = (new Builder())->issuedBy('http://master.j1beatz.com')
                ->permittedFor('http://master.j1beatz.com')
                ->identifiedBy(mt_rand().'k', true)  //토큰고유식별번호
                ->issuedAt($time)            //iss시간 대응
                ->canOnlyBeUsedAfter($time)  //nbf에 대응
                ->expiresAt($time + 900)
                ->withClaim('uid', -10)   //최고관리자, -10
                ->getToken($signer, new Key(config('constant.JWT_SECRET_KEY'))); // Retrieves the generated token
        } catch (exception $e) {
            return null;
        }

        return  Encryptor::get_instance()->aes256_encryption($token, config('constant.AES_SECRET_KEY'));
    }

    public function decode_tkn($token)
    {
        $res =null;
        
        
        $token =  urldecode($token);

        $token = Encryptor::get_instance()->aes256_decryption($token, config('constant.AES_SECRET_KEY'));
             
        try {
            $signer = new Sha256();

            $token = (new Parser())->parse((string) $token); // Parses from a string

            if ($token->verify($signer, config('constant.JWT_SECRET_KEY'))) {
                $res = $token;
            }
        } catch (\Throwable  $e) {
            $res =null;
        }

        return $res;
    }

    public static function get_instance()
    {
        return new JWT();
    }
}
