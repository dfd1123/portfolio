<?php 

namespace App\Http\Utils;


class Encryptor {

    public function aes256_encryption($input , $secret_key){
        
    
        return base64_encode(openssl_encrypt($input, 'aes-256-cbc'
        , $secret_key, OPENSSL_RAW_DATA, config('constant.AES_iv')));
    }


    public function aes256_decryption($input , $secret_key){
    
        return openssl_decrypt(base64_decode($input), 'aes-256-cbc'
        , $secret_key, OPENSSL_RAW_DATA, config('constant.AES_iv'));

    }

    public static function get_instance()
    {
        return  new Encryptor();
    }
}
