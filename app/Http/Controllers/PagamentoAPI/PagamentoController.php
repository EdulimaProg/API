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

        //tranforma o cartão em array
        $card = $request->card;

        //solicitação a api get net
        $operacao = new Gnet();
        //geração de numero de pedido
        $code = new ConsultaService();

        //bearer token
        $bearer = $operacao->getToken();

        $name_device = $_SERVER['HTTP_USER_AGENT'] != null ? $_SERVER['HTTP_USER_AGENT']:'Desconhecido';

        //dd($card);
        //tokenização do cartão
        $token_cartao = $operacao->token_card($bearer, $request->number);

        $arr = [
            "brand" => $request->brand,
            "cardholder_name" =>  $request->cardholder_name,
            "expiration_month" =>  $request->expiration_month,
            "expiration_year" =>  $request->expiration_year,
            "security_code" =>  $request->security_code
        ];

        //dd($arr['cardholder_name']);
        $ip = $operacao->getUserIP();
        //verificação de cartão
        $veifica_cartao = $operacao->verifica_cartao($token_cartao, json_encode($arr), $bearer);

        //dd($token_cartao);
        //requisição de pagamento
        $form = [
            "seller_id"=> getenv('SELLER_ID_SANDBOX'),
            "amount"=> $request->valor,
            "currency" => "BRL",
            "order"=> [
                "order_id" => $request->numero_pedido,
                "sales_tax" => 0,
                "product_type" => "service"
            ],
            "customer"=> [
                "customer_id"=> 01,
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
                    "cardholder_name"=> $request->cardholder_name,
                    "security_code"=> $request->security_code,
                    "expiration_month"=> $request->expiration_month,
                    "expiration_year"=> $request->expiration_year
                ]
            ]
        ];
        //solicitação guzzle
        $client = new Client();

        $header_req = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ". $bearer,
        ];

        $url = getenv('API_URL_SANDBOX').'/v1/payments/credit';

        try {

            $request = $client->post($url,['headers' => $header_req, 'json' => $form]);

            $response = $request->getBody()->getContents();

        }catch (RequestException $e){

            $arr = [
                "nome" => $e->getCode(),
                "tipo" => "Pagamento Não Autorizado"
            ];

            $response = json_encode($arr);

        }

        return response()->json(json_decode($response));
    }
}
