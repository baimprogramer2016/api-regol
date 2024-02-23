<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use LZCompressor\LZString;

trait SignatureBPJS
{
    public function index()
    {
        return 'Antrol';
    }

    public function SignatureAntrol()
    {
        $consId = env('CID');
        $secretKey = env('SK');
        $userKey = env('UK');

        // Computes the timestamp
        date_default_timezone_set('UTC');

        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        // Computes the signature by hashing the salt with the secret key as the key
        $signature = hash_hmac('sha256', $consId . "&" . $tStamp, $secretKey, true);

        // base64 encode
        $encodedSignature = base64_encode($signature);

        // urlencode
        // $encodedSignature = urlencode($encodedSignature);

        return [
            "x-cons-id" => $consId,
            "x-secret-key" => $secretKey,
            "x-timestamp" => $tStamp,
            "x-signature" => $encodedSignature,
            "x-user-key" =>  $userKey
        ];
    }

    function stringDecrypt($key, $string)
    {
        $encrypt_method = 'AES-256-CBC';

        // hash
        $key_hash = hex2bin(hash('sha256', $key));

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        return $output;
    }
    // function lzstring decompress
    // download libraries lzstring : https://github.com/nullpunkt/lz-string-php
    function decompress($string)
    {
        return LZString::decompressFromEncodedURIComponent($string);
    }

    function getDataBpjs($param)
    {
        # get xonst
        $signature = $this->SignatureAntrol();

        $url = $param['url'];

        # proses tarik data
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-cons-id' => $signature['x-cons-id'],
            'x-timestamp' => $signature['x-timestamp'],
            'x-signature' => $signature['x-signature'],
            'user_key' => $signature['x-user-key']
        ])->get($url);

        # parse
        $result = json_decode($response, true);

        # buat key key decrypt & decompres
        $keyDecrypt = $signature['x-cons-id'] . $signature['x-secret-key'] . $signature['x-timestamp'];
        $resultDecrypt   = $this->stringDecrypt($keyDecrypt, $result['response']);
        $resultDecompres   = $this->decompress($resultDecrypt);

        return $resultDecompres;
    }
    function getDataBpjs2($param)
    {
        # get xonst
        $signature = $this->SignatureAntrol();

        $url = $param['url'];

        # proses tarik data
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-cons-id' => $signature['x-cons-id'],
            'x-timestamp' => $signature['x-timestamp'],
            'x-signature' => $signature['x-signature'],
            'user_key' => $signature['x-user-key']
        ])->get($url);

        # parse
        $result = json_decode($response, true);

        if ($result['response'] != null) {
            # buat key key decrypt & decompres
            $keyDecrypt = $signature['x-cons-id'] . $signature['x-secret-key'] . $signature['x-timestamp'];
            $resultDecrypt   = $this->stringDecrypt($keyDecrypt, $result['response']);
            $resultDecompres   = $this->decompress($resultDecrypt);

            return $resultDecompres;
        } else {
            return $response;
        }
    }
}
