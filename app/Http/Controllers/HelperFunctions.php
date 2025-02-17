<?php

namespace App\Http\Controllers;

class HelperFunctions {

    public static function base64_url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64_url_decode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder); // Add padding
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}