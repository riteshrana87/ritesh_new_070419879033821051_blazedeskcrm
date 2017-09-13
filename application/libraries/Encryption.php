<?php
/**
 * Created by PhpStorm.
 * User: priyankam@weboniselab.com
 * Date: 3/10/2016
 * Time: 3:06 PM
 */


class Encryption {

    var $skey = "1qazxsw2"; // you can change it

    public  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public  function encode($value){
        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function decode($value){
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    public  function mail_client_url($value){
        if(!$value){return false;}

        if(($value['email_id']!='') && ($value['email_pass']!='')){
         $userEmail='user='.$value['email_id'].'&';
         $userPass='pass='.$this->decode($value['email_pass']).'&';
         $autoLogin='autologin=true';

         $url['encoded']= base64_encode($userEmail.$userPass.$autoLogin);

        }else{
            return false;
        }

        return $url;
    }
}