<?php

namespace App\Servicos;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Gnet{

    public static function getToken(){

        $client = new Client();

        $end = getenv('API_URL_SANDBOX').'/auth/oauth/v2/token';
        $key = 'Basic '.base64_encode(getenv('CLIENT_ID_SANDBOX').':'.getenv('SECRET_ID_SANDBOX'));
        //dd($key);
        //dd($end);

        $response = $client->request('POST', $end, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization'      => $key
            ],
            'form_params' => [
                'scope' => 'obb',
                'grant_type' => 'client_credentials'
            ],
        ]);

        $token = json_decode($response->getBody()->getContents());


        return $token->access_token;
    }
    public static function token_card($bearer,$card_number){

        $client = new Client();

        $end = getenv('API_URL_SANDBOX').'/v1/tokens/card';
        $key = 'Bearer '. $bearer;

//        dd($key, $card_number);

        $response = $client->request('POST', $end, [
            'headers' => [
                'Content-Type' => "application/x-www-form-urlencoded",
                'Authorization' => $key,
                'seller_id' => getenv('SELLER_ID_SANDBOX')
            ],
            'form_params' => [
                'card_number' => $card_number,
                "customer_id" => "customer_21081826"
            ],
        ]);
        //dd($response->getBody()->getContents());
        $token = json_decode($response->getBody()->getContents());

        return $token->number_token;
    }
    public static function verifica_cartao($number_token, $dados, $bearer){

        $dados_cartao = json_decode($dados);

        $client = new Client();

        $end = getenv('API_URL_SANDBOX').'/v1/cards/verification';
        $key = 'Bearer '. $bearer;

//        dd($key, $card_number);

        $response = $client->request('POST', $end, [
            'headers' => [
                'Content-Type' => "application/x-www-form-urlencoded",
                'Authorization' => $key,
                'seller_id' => getenv('SELLER_ID_SANDBOX')
            ],
            'form_params' => [
                'number_token' =>       $number_token,
                "brand" =>              $dados_cartao->brand,
                "cardholder_name" =>    $dados_cartao->cardholder_name,
                "expiration_month" =>   $dados_cartao->expiration_month,
                "expiration_year" =>    $dados_cartao->expiration_year,
                "security_code" =>      $dados_cartao->security_code
            ],
        ]);
        //hue
        //dd($response->getBody()->getContents());
        $token = json_decode($response->getBody()->getContents());
        return $token;
    }
    public static function efetua_pagamento(){
    }
    public static function getUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
