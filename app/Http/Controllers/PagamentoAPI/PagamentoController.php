<?php

namespace App\Http\Controllers\PagamentoAPI;

use App\Servicos\ConsultaService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Servicos\Gnet;


class PagamentoController extends Controller
{
    public function Pagamento(Request $request){

        $card = $request->card;


        $operacao = new Gnet();
        $code = new ConsultaService();

        $bearer = $operacao->getToken();

        $name_device = $_SERVER['HTTP_USER_AGENT'] != null ? $_SERVER['HTTP_USER_AGENT']:'Desconhecido';

        //dd($card);

        $token_cartao = $operacao->token_card($bearer, $card['number']);

        $arr = [
            "brand" => "Mastercard",
            "cardholder_name" =>  "JOAO DA SILVA",
            "expiration_month" =>  "12",
            "expiration_year" =>  "20",
            "security_code" =>  "123"
        ];

        $ip = $operacao->getUserIP();

        $veifica_cartao = $operacao->verifica_cartao($token_cartao, json_encode($card), $bearer);

        //dd($token_cartao);
        $form = [
            "seller_id"=> getenv('SELLER_ID_SANDBOX'),
            "amount"=> 100,
            "currency" => "BRL",
            "order"=> [
                "order_id" => "552881",
                "sales_tax" => 0,
                "product_type" => "service"
            ],
            "customer"=> [
                "customer_id"=> " ".rand(1000000,9000000)." ",
                "billing_address"=> [
                    "street" => "Av. Brasil",
                    "number" => "1000",
                    "complement" => "Sala 1",
                    "district" => "São Geraldo",
                    "city" => "Porto Alegre",
                    "state" => "RS",
                    "country" => "Brasil",
                    "postal_code" => "90230060"
                ],
            ],
            "credit"=> [
                "delayed"=> false,
                "pre_authorization"=> false,
                "save_card_data"=> false,
                "transaction_type"=> "FULL",
                "number_installments"=> 1,
                "soft_descriptor"=>"pagamento de plano",
                "card"=> [
                    "number_token"=> $token_cartao,
                    "cardholder_name"=> "JOAO DA SILVA",
                    "security_code"=> "123",
                    "expiration_month"=> "12",
                    "expiration_year"=> "20"
                ]
            ]
        ];

        //dd(json_encode($form));

        $client = new Client();

        $header_req = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ". $bearer,
        ];


        $url = getenv('API_URL_SANDBOX').'/v1/payments/credit';

        $request = $client->post($url,['headers' => $header_req, 'json' => $form]);

        return response()->json(json_decode($request->getBody()->getContents()));
    }
}
