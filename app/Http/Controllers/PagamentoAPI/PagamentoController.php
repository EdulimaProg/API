<?php

namespace App\Http\Controllers\PagamentoAPI;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Servicos\Gnet;


class PagamentoController extends Controller
{
    public function Pagamento(Request $request){

        $operacao = new Gnet();

        $bearer = $operacao->getToken();

        $token_cartao = $operacao->token_card($bearer, "5155901222280001");

        $arr = [
            "brand" => "Mastercard",
            "cardholder_name" =>  "JOAO DA SILVA",
            "expiration_month" =>  "12",
            "expiration_year" =>  "20",
            "security_code" =>  "123"
        ];

        $ip = $operacao->getUserIP();

        $veifica_cartao = $operacao->verifica_cartao($token_cartao, json_encode($arr), $bearer);


        $client = new Client();

        //$form = ;

        //dd($form);

        $url = getenv('API_URL_SANDBOX').'/v1/payments/credit';

        $response = $client->request("POST",$url,[
                'headers' => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "Authorization" => "Bearer ". $bearer,
                ],
                "form_params" => [
                    "seller_id"=> getenv('SELLER_ID_SANDBOX'),
                    "amount"=> 100,
                    "currency"=> "BRL",
                    "order"=> [
                        "order_id"=> "6d2e4380-d8a3-4ccb-9138-c289182818a3",
                        "sales_tax"=> 0,
                        "product_type"=> "service"
                    ],
                    "customer"=> [
                        "customer_id"=> "customer_21081826",
                        "first_name"=> "João",
                        "last_name"=> "da Silva",
                        "name"=> "João da Silva",
                        "email"=> "customer@email.com.br",
                        "document_type"=> "CPF",
                        "document_number"=> "12345678912",
                        "phone_number"=> "5551999887766",
                        "billing_address"=> [
                            "street"=> "Av. Brasil",
                            "number"=> "1000",
                            "complement"=> "Sala 1",
                            "district"=> "São Geraldo",
                            "city"=> "Porto Alegre",
                            "state"=> "RS",
                            "country"=> "Brasil",
                            "postal_code"=> "90230060"
                        ]
                    ],
                    "credit"=> [
                        "delayed"=> false,
                        "authenticated"=> false,
                        "pre_authorization"=> false,
                        "save_card_data"=> false,
                        "transaction_type"=> "FULL",
                        "number_installments"=> 1,
                        "soft_descriptor"=> "LOJA*TESTE*COMPRA-123",
                        "dynamic_mcc"=> 1799,
                        "card"=> [
                            "number_token"=> $token_cartao,
                            "cardholder_name"=> "JOAO DA SILVA",
                            "security_code"=> "123",
                            "brand"=> "Mastercard",
                            "expiration_month"=> "12",
                            "expiration_year"=> "20"
                        ]
                    ]
                ]
            ]
        );

        dd($response->getBody()->getContents());

        return response()->json($bearer);
    }
}
