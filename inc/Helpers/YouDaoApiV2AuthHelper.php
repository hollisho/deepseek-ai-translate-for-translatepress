<?php
namespace hollisho\translatepress\translate\deepseek\inc\Helpers;

class YouDaoApiV2AuthHelper {

    public static function convert(&$args)
    {
        $data = '';
        if (is_array($args))
        {
            foreach ($args as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k=>$v)
                    {
                        $data .= 'q='.rawurlencode($v).'&';
                    }
                }
                else
                {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }

// uuid generator
    public static function create_guid(){
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);
        self::ensure_length($dec_hex, 5);
        self::ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= self::create_guid_section(3);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= self::create_guid_section(6);
        return $guid;
    }

    public static function create_guid_section($characters){
        $return = "";
        for($i = 0; $i < $characters; $i++)
        {
            $return .= dechex(wp_rand(0,15));
        }
        return $return;
    }

    public static function truncate($q) {
        $len = self::abslength($q);
        return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
    }

    public static function abslength($str)
    {
        if(empty($str)){
            return 0;
        }
        if(function_exists('mb_strlen')){
            return mb_strlen($str,'utf-8');
        }
        else {
            preg_match_all("/./u", $str, $ar);
            return count($ar[0]);
        }
    }

    public static function ensure_length(&$string, $length){
        $strlen = strlen($string);
        if($strlen < $length)
        {
            $string = str_pad($string, $length, "0");
        }
        else if($strlen > $length)
        {
            $string = substr($string, 0, $length);
        }
    }
}


