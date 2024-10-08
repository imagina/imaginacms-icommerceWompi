<?php

namespace Modules\Icommercewompi\Http\Controllers;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base
use Modules\Core\Http\Controllers\BasePublicController;

// Repositories
use Modules\Icommercewompi\Repositories\IcommerceWompiRepository;

use Modules\Icommerce\Repositories\PaymentMethodRepository;
use Modules\Icommerce\Repositories\TransactionRepository;
use Modules\Icommerce\Repositories\OrderRepository;

use Modules\Icommercewompi\Repositories\PaymentSourcesRepository;


class PublicController extends BasePublicController
{

    private $icommercewompi;
    private $paymentMethod;
    private $order;
    private $transaction;
    private $log = "Icommercewompi: PublicController|";
    protected $urls;
    private $paymentSources;

    public function __construct(
        IcommerceWompiRepository $icommercewompi,
        PaymentMethodRepository $paymentMethod,
        OrderRepository $order,
        TransactionRepository $transaction,
        PaymentSourcesRepository $paymentSources
    )
    {
        $this->icommercewompi = $icommercewompi;
        $this->paymentMethod = $paymentMethod;
        $this->order = $order;
        $this->transaction = $transaction;
        $this->paymentSources = $paymentSources;
       
        $this->urls['sandbox'] = "https://checkout.wompi.co/p/";
        $this->urls['production'] = "https://checkout.wompi.co/p/";
    }


    /**
     * Index data
     * @param Requests request
     * @return route
     */
    public function index($eURL)
    {

        try {

            // Decr
            $infor = icommercewompi_decriptUrl($eURL);
            $orderID = $infor[0];
            $transactionID = $infor[1];
           
            //\Log::info('Module Icommercewompi: Index-ID:'.$orderID);

            // Validate get data
            $order = $this->order->find($orderID);
            $transaction = $this->transaction->find($transactionID);
           
            // Get Payment Method Configuration
            $paymentMethod = $this->paymentMethod->getItem($order->payment_code);

            
            if($paymentMethod->name=="icommercewompi"){
                //Webcheckout Implementation
                $wompiService = app("Modules\Icommercewompi\Services\WompiService");
                $wompi = $wompiService->create($paymentMethod,$order,$transaction,$this->urls);

                $wompi->executeRedirection();

            }else{

                //Payment Sources Implementation
                $publicKey = $paymentMethod->options->publicKey;
                $tpl = 'icommercewompi::frontend.index';

                //Get User Payment Sources
                $params = ['filter' => ['user_id' => \Auth::id() ?? null],'take' => 10];
                $userPaymentSources =  app("Modules\Icommercewompi\Repositories\PaymentSourcesRepository")->getItemsBy(json_decode(json_encode($params)));
                
                //Final url to redirect
                $redirectUrl = url('/icommercewompi/payment/'.$eURL.'/');

                return view($tpl, compact('eURL','publicKey','userPaymentSources','order','redirectUrl'));
            }
            
            


        } catch (\Exception $e) {

            \Log::error($this->log.'Index|Message: '.$e->getMessage());
            \Log::error($this->log.'Index: Code: '.$e->getCode());

            //Message Error
            $status = 500;
            $response = [
              'errors' => $e->getMessage(),
              'code' => $e->getCode()
            ];

            return redirect()->route("homepage");

        }

    }


    /**
    * Response Frontend After the Payment
    * @param  $request (transaction wompi id)
    * @param  $orderId
    * @return redirect
    */
    public function response(Request $request,$orderId)
    {

        $locale = \LaravelLocalization::setLocale() ?: \App::getLocale();
        $isQuasarAPP = env("QUASAR_APP", false);

        if(isset($request->id)){

           
            $order = $this->order->find($orderId);

            if(!$isQuasarAPP){
                if (!empty($order))
                    return redirect($order->url);
                else
                    return redirect()->route('homepage');

            }else{
                return view('icommerce::frontend.orders.closeWindow');
            }

        }else{

          if(!$isQuasarAPP){
            return redirect()->route('homepage');
          }else{
            return view('icommerce::frontend.orders.closeWindow');
          }

        }

    }

    /**
     * Payment | Case Payment Source | PS Selected in Index
     */
    public function payment($eURL,$ePS)
    {

        try {

            // Decr
            $infor = icommercewompi_decriptUrl($eURL);
            $orderID = $infor[0];
            $transactionID = $infor[1];
           
            // Validate get data
            $order = $this->order->find($orderID);
            $transaction = $this->transaction->find($transactionID);
           
            // Get Payment Method Configuration
            $paymentMethod = $this->paymentMethod->getItem($order->payment_code);

            //Validation Payment Sources with User
            $inforPS = icommercewompi_decriptPS($ePS);
            \Log::info($this->log.'payment|PS Id:'.$inforPS[1]);

            $params = ['filter' => ['user_id' => \Auth::id() ?? null]];
            $psInfor = $this->paymentSources->getItem($inforPS[1],json_decode(json_encode($params)));
            if(!isset($psInfor->id))
                throw new \Exception("Error - PS does not correspond to the logged in user", 204);

            //Api Controller
            $wompiApi = app("Modules\Icommercewompi\Http\Controllers\Api\WompiApiController");
            
            //Create Transaction
            $options = ["ps_id" => $psInfor->options->ps_id,"type" => $psInfor->options->type];
            $dataTransaction = $wompiApi->createTransaction($options,$order,$transaction,$paymentMethod);

            //Validate Transaction
            if(!isset($dataTransaction->id))
                throw new \Exception("Error - No transaction id", 204);

            \Log::info($this->log.'payment|END');
            
            //Final Return
            return redirect($order->url);

            
        } catch (\Exception $e) {

            \Log::error($this->log.'Payment|Message: '.$e->getMessage());
            \Log::error($this->log.'Payment|Code: '.$e->getCode());

            //Message Error
            $status = 500;
            $response = ['errors' => $e->getMessage(),'code' => $e->getCode()];

            return redirect()->route("homepage");

        }

    }


}
