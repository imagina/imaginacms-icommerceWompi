<?php

namespace Modules\Icommercewompi\Services;

use Modules\Icommercewompi\Entities\Wompi as WompiEntity;

class WompiService
{

    private $log = "Icommercewompi: WompiService|";

	public function __construct(){

	}

    /**
    * Configuration to reedirect
    * @param 
    * @return Object Configuration
    */
	public function create($paymentMethod,$order,$transaction,$urls=null)
    {

		$wompi = new WompiEntity();

        if(!is_null($urls)){
            //Case Webcheckout
            $urlWompi = $paymentMethod->options->mode=="sandbox" ? $urls['sandbox'] : $urls['production'];
        }else{
            //Case API
            $urlWompi = $this->getApiEnviroment($paymentMethod,"transactions");
        }

        $wompi->setUrlgate($urlWompi);
        $wompi->setPublicKey($paymentMethod->options->publicKey);
        $wompi->setReferenceCode(icommercewompi_getOrderRefCommerce($order,$transaction));
        $wompi->setAmount(round($order->total)); //No admite decimales
        $wompi->setCurrency($order->currency_code);
        $wompi->setSignatureIntegrity($this->makeSignatureIntegrity($wompi,$paymentMethod->options->signatureIntegrityKey));
        $wompi->setRedirectUrl(Route("icommercewompi.response",$order->id));

        return $wompi;

	}

     /**
     * Make the Signature
     * @param Requests request
     * @param Payment Method
     * @return signature
     */
    public function makeSignature($request,$paymentMethod)
    {


        $transaction = $request->data['transaction'];
        $signatureProps = $request->signature['properties'];

        $concatProps = "";
        foreach ($signatureProps as $key => $prop) {
            $result = explode('.',$prop);
            $concatProps.=$transaction[$result[1]];
        }
       
        $concatProps.=$request->timestamp.$paymentMethod->options->eventSecretKey;
        
        $signature = hash('sha256',$concatProps);

        return $signature;

    }

     /**
     * Get Status to Order
     * @param Int cod
     * @return Int 
     */
    public function getStatusOrder($cod)
    {

        switch ($cod) {

            case "APPROVED": // Aceptada
                $newStatus = 13; //processed
            break;

            case "DECLINED": // Rechazada
                $newStatus = 5; //denied
            break;

            case "VOIDED": // Transacción anulada (sólo aplica pra transacciones con tarjeta)
                $newStatus = 12; //voided
            break;

            case "ERROR": // Fallida
                $newStatus = 7; //failed
            break;


        }
        
        \Log::info('Icommercewompi: New Status: '.$newStatus);

        return $newStatus; 

    }

    /**
     * Make the Signature Integrity - Update 2023
     * @param Wompi (Class with configurations)
     * @param signInteKey (signatureIntegrityKey from DB)
     * @return signature
     */
    public function makeSignatureIntegrity($wompi,$signInteKey)
    {   
        $signature = hash ("sha256", $wompi->referenceCode."".$wompi->amount."".$wompi->currency."".$signInteKey);
        return $signature;
    }

    /**
     * Get Enviroment
     */
    public function getApiEnviroment($paymentMethod,$process)
    {
        
        $config = config('asgard.icommercewompi.config');
        //Set Endpoint
        $urlWompi = $config['apiUrl'][$process]['sandbox'];
        if ($paymentMethod->options->mode != 'sandbox') 
            $urlWompi = $config['apiUrl'][$process]['production'];

        return $urlWompi;
    }

    /**
     * Configuration to Transaction | API
     * @param psId (payment source id)
     */
    public function makeConfigurationToTransaction($paymentMethod,$order,$transaction,$psData)
    {
       
        //Create Basic Configurations
        $wompi = $this->create($paymentMethod,$order,$transaction);
       
        //Set Data Transaction
        $data = [
            "amount_in_cents" => $wompi->amount,
            "currency" => $wompi->currency,
            "signature" => $wompi->signatureIntegrity,
            "customer_email" => $order->email,
            "reference" => $wompi->referenceCode,
            "payment_source_id" => $psData['ps_id'],
            "recurrent"=> true
        ];

        //Case Card
        if($psData['type']=="CARD")
            $data['payment_method']['installments'] = 1;
        
        //Final data
        return [
            'data' => $data,
            'endpoint' => $wompi->urlAction
        ];

    }

    /**
     * Fix data To Save After
     */
    public function fixDataPaymentSources($paymentSourceData)
    {
        
        $finalData = ["ps_id" => $paymentSourceData->id,"type" => $paymentSourceData->type];
            
        //Validation CARD
        if($paymentSourceData->type=="CARD"){
            $finalData["last_four"] = $paymentSourceData->public_data->last_four;
            $finalData["card_holder"] = $paymentSourceData->public_data->card_holder;
        }

        //Validation NEQUI
        if($paymentSourceData->type=="NEQUI"){
            $finalData["phone"] = $paymentSourceData->public_data->phone_number;
        }

        return $finalData;
    }

    /**
     * Get User Payment Sources Data | Testing Method with API
     */
    public function getUserPaymentSources($paymentMethod,$eUrl)
    {

        \Log::info($this->log.'getUserPaymentSources');

        $userPaymentSources = [];

        $params = ['filter' => ['user_id' => \Auth::id() ?? null],'take' => 10];

        $userPS =  app("Modules\Icommercewompi\Repositories\PaymentSourcesRepository")->getItemsBy(json_decode(json_encode($params)));

        if(count($userPS)>0){
            $wompiApi = app("Modules\Icommercewompi\Http\Controllers\Api\WompiApiController");
            foreach ($userPS as $key => $paymentSource) {

                //Get Infor from API
                $paymentSourceData = $wompiApi->getPaymentSource($paymentSource->options->ps_id,$paymentMethod);
                if($paymentSourceData->status=="AVAILABLE"){

                    \Log::info($this->log.'getUserPaymentSources|PS Id:'.$paymentSource->id);

                    $finalData = [
                        "id" => $paymentSource->id,
                        "type" => $paymentSourceData->type,
                        "payment_url" => route('icommercewompi.payment',['eUrl'=> $eUrl,'ps'=>  icommercewompi_encriptPS($paymentSource->id)])
                    ];

                    if($paymentSourceData->type=="CARD"){
                        $finalData["last_four"] = $paymentSourceData->public_data->last_four;
                        $finalData["card_holder"] = $paymentSourceData->public_data->card_holder;
                    }

                    if($paymentSourceData->type=="NEQUI"){
                        $finalData["last_four"] = $paymentSourceData->public_data->phone_number;
                        $finalData["card_holder"] = "";
                    }
                   
                    array_push($userPaymentSources,$finalData);
                }   
                
            }
        }

        return $userPaymentSources;
        
    }

    /**
     * 
     */
    public function checkUserPaymentSourcesExists($newPaymentSources)
    {

        \Log::info($this->log.'checkUserPaymentSourcesExists');
       
        $params = ['filter' => ['user_id' => \Auth::id() ?? null]];
        $userPaymentSources =  app("Modules\Icommercewompi\Repositories\PaymentSourcesRepository")->getItemsBy(json_decode(json_encode($params)));

        $psToDelete = null;

        if(count($userPaymentSources)>0){
            foreach ($userPaymentSources as $key => $paymentSource) {
                //Case CARD
                if($paymentSource->options->type=="CARD" && $newPaymentSources->public_data->type=="CARD"){
                    //Exist
                    if($paymentSource->options->last_four==$newPaymentSources->public_data->last_four){
                        $psToDelete = $paymentSource;
                        break;
                    }
                }
                //CASE NEQUI
                if($paymentSource->options->type=="NEQUI" && $newPaymentSources->public_data->type=="NEQUI"){
                    //Exist
                    if($paymentSource->options->phone==$newPaymentSources->public_data->phone){
                        $psToDelete = $paymentSource;
                        break;
                    }
                }
            }
        }

        if(!is_null($psToDelete)){
            $psToDelete->delete();
        }
    }

}