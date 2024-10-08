<?php

namespace Modules\Icommercewompi\Services;

use Modules\Icommercewompi\Http\Controllers\Api\WompiApiController;
use Modules\Icommerce\Repositories\TransactionRepository;


use Modules\Icommercewompi\Repositories\PaymentSourcesRepository;

class RecurrenceService
{   

    private $log = "Icommercewompi: RecurrenceService|";
    private $wompiApi;
    private $paymentSources;
    private $transactionRepository;


    public function __construct(
        WompiApiController $wompiApi,
        PaymentSourcesRepository $paymentSources,
        TransactionRepository $transactionRepository
    ){
        $this->wompiApi = $wompiApi;
        $this->paymentSources = $paymentSources;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * This validation is carried out because if the same module has recurrence, the namespace is the same
     */
    public function isRecurrence($moduleName)
    {
        if($moduleName!="icommercewompipaymentsources")
            return false;

        return true;
    }

    /**
    * Process to Recurrence
    */
	public function init($order,$paymentMethod)
    {
        
        \Log::info($this->log."INIT");

        //Default
        $response = ['status' => 1 ];

        //Get Payment Sources
        $params = ["filter" => ["field" => "user_id","default" => 1,"status" => 1] ];
        $paymentSource = $this->paymentSources->getItem($order->customer_id, json_decode(json_encode($params)));

        if(!is_null($paymentSource)){

            //Create Transaction | Remember in checkout case this is created in method init
            $transaction = $this->transactionRepository->create([
                'order_id' => $order->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $order->total,
                'status' => $order->status_id
            ]);

            //Payment Source Data to array
            $psData = (array)$paymentSource->options;

            //Create Transaction
            $dataTransaction = $this->wompiApi->createTransaction($psData,$order,$transaction,$paymentMethod);

            //Save to response
            $response['data'] = $dataTransaction;
            

        }else{
            $response = ['status' => 0];
            \Log::error($this->log."Payment Source | Not Found");
        }

        \Log::info($this->log."END");

        return $response;
		
	}

    

}