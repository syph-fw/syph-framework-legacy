<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 07/10/2016
 * Time: 23:59
 */

namespace Syph\Security\Crypt;


class Cube
{
    public static function generateHash($str = null, $key = null, $alg = 'sha256')
    {
        $str = null === $key ? self::generateRandomString() : $str;
        $key = null === $key ? uniqid() : $key;
        return hash_hmac($alg,$str,$key);
    }

    public static function checkHashEqual($a, $b)
    {
        $diff = strlen($a) ^ strlen($b);
        for($i = 0; $i < strlen($a) AND $i < strlen($b); $i++)
        {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $diff === 0;
    }

    public static function generateRandomString($length = 10) {
        return substr(
            str_shuffle(
                str_repeat(
                    $x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    ceil( $length / strlen($x) )
                )
            ),1,$length
        );
    }
}