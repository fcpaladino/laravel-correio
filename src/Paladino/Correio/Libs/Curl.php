<?php
namespace Paladino\Correio\Libs;


class Curl
{

    public static function send($url, $post = [], $get = [])
    {
        $url = explode('?', $url, 2);

        if(count($url)===2){
            $temp_get = array();
            parse_str($url[1], $temp_get);
            $get = array_merge($get, $temp_get);
        }

        $ch = curl_init($url[0]."?".http_build_query($get));

        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec ($ch);

        curl_close($ch);

        return $response;
    }

}