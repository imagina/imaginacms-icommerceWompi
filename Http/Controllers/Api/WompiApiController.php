<?php

namespace Modules\Icommercewompi\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

use Modules\Icommerce\Repositories\TransactionRepository;
use Modules\Icommerce\Repositories\OrderRepository;


class WompiApiController extends BaseApiController
{
    private $order;
    private $transaction;
    private $wompiService;
    private $log = "Icommercewompi: WompiApiController|";

    public function __construct(
        OrderRepository $order,
        TransactionRepository $transaction
    ){
        $this->order = $order;
        $this->transaction = $transaction;
        $this->wompiService = app("Modules\Icommercewompi\Services\WompiService");
    }

    /**
     * API Wompi | Get acceptance_token
     */
    public function getAcceptanceToken($paymentMethod)
    {
        \Log::info($this->log."getAcceptanceToken");
        
        $endpoint = $this->wompiService->getApiEnviroment($paymentMethod,"acceptanceToken")."".$paymentMethod->options->publicKey;

        //Request
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);

        $res = json_decode($response->getBody()->getContents());

        return $res->data->presigned_acceptance->acceptance_token;
    }

    /**
     * API Wompi | Create Payment Source
     */
    public function createPaymentSource($paymentToken,$email,$acceptanceToken,$paymentMethod)
    {
        \Log::info($this->log."createPaymentSource");
        
        $endpoint = $this->wompiService->getApiEnviroment($paymentMethod,"paymentSources");

        //Set Data
        $data = [
            "type" => $paymentToken['payment_source_type'],
            "token" => $paymentToken['payment_source_token'],
            "customer_email" => $email,
            "acceptance_token" => $acceptanceToken
        ];

        //Request
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $endpoint, [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer '.$paymentMethod->options->privateKey
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return $res->data;
    }

    /**
     * API Wompi | Create Transaction 
     */
    public function createTransaction($paymentSourceData,$order,$transaction,$paymentMethod)
    {   
        
        \Log::info($this->log."createTransaction");

        //Make Configuration
        $infor = $this->wompiService->makeConfigurationToTransaction($paymentMethod,$order,$transaction,$paymentSourceData);

        //Request
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $infor['endpoint'], [
            'body' => json_encode($infor['data']),
            'headers' => [
                'Authorization' => 'Bearer '.$paymentMethod->options->privateKey
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());
       
        return $res->data;

    }

    /**
     * 
     */
    public function getPaymentSource($paymentSourcesId,$paymentMethod)
    {
        \Log::info($this->log."getPaymentSources");
        
        $endpoint = $this->wompiService->getApiEnviroment($paymentMethod,"paymentSources");

        //Request
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint."/".$paymentSourcesId,[
            'headers' => [
                'Authorization' => 'Bearer '.$paymentMethod->options->privateKey
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return $res->data;
    }

}