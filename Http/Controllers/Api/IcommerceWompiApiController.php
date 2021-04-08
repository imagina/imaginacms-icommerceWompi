<?php

namespace Modules\Icommercewompi\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Icommercewompi\Http\Requests\InitRequest;

// Base Api
use Modules\Icommerce\Http\Controllers\Api\OrderApiController;
use Modules\Icommerce\Http\Controllers\Api\TransactionApiController;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

// Repositories
use Modules\Icommercewompi\Repositories\IcommerceWompiRepository;

use Modules\Icommerce\Repositories\PaymentMethodRepository;
use Modules\Icommerce\Repositories\TransactionRepository;
use Modules\Icommerce\Repositories\OrderRepository;


class IcommerceWompiApiController extends BaseApiController
{

    private $icommercewompi;
    private $paymentMethod;
    private $order;
    private $orderController;
    private $transaction;
    private $transactionController;

    public function __construct(

        IcommerceWompiRepository $icommercewompi,
        PaymentMethodRepository $paymentMethod,
        OrderRepository $order,
        OrderApiController $orderController,
        TransactionRepository $transaction,
        TransactionApiController $transactionController
    ){
        $this->icommercewompi = $icommercewompi;
        $this->paymentMethod = $paymentMethod;
        $this->order = $order;
        $this->orderController = $orderController;
        $this->transaction = $transaction;
        $this->transactionController = $transactionController;
    }

    /**
     * Init data
     * @param Requests request
     * @param Requests orderId
     * @return route
     */
    public function init(Request $request){


        try {

            $data = $request->all();
           
            $this->validateRequestApi(new InitRequest($data));

            $orderID = $request->orderId;
            //\Log::info('Module Icommercewompi: Init-ID:'.$orderID);

            // Payment Method Configuration
            $paymentMethod = icommercewompi_getPaymentMethodConfiguration();

            // Order
            $order = $this->order->find($orderID);
            $statusOrder = 1; // Processing

            // Validate minimum amount order
            if(isset($paymentMethod->options->minimunAmount) && $order->total<$paymentMethod->options->minimunAmount)
              throw new \Exception(trans("icommercewompi::icommercewompis.messages.minimum")." :".$paymentMethod->options->minimunAmount, 204);

            // Create Transaction
            $transaction = $this->validateResponseApi(
                $this->transactionController->create(new Request( ["attributes" => [
                    'order_id' => $order->id,
                    'payment_method_id' => $paymentMethod->id,
                    'amount' => $order->total,
                    'status' => $statusOrder
                ]]))
            );

            // Encri
            $eUrl = icommercewompi_encriptUrl($order->id,$transaction->id);

            $redirectRoute = route('icommercewompi',[$eUrl]);

            // Response
            $response = [ 'data' => [
                "redirectRoute" => $redirectRoute,
                "external" => true
            ]];


          } catch (\Exception $e) {
           \Log::error($e->getMessage());
            $status = 500;
            $response = [
              'errors' => $e->getMessage()
            ];
        }


        return response()->json($response, $status ?? 200);

    }

      /**
     * Response Api Method - Confirmation
     * @param Requests request
     * @return route
     */
    public function confirmation(Request $request){

        try {

            \Log::info('Module Icommercewompi: Confirmation - '.time());


            $wompiTransaction = $request->data->transaction;

            // Get IDS
            $referenceSale = explode('-',$wompiTransaction->id);
            $orderID = $referenceSale[0];
            $transactionID = $referenceSale[1];

            \Log::info('Module Icommercewompi: Confirmation - orderID '.$orderID);

             // Order
            $order = $this->order->find($orderID);

            \Log::info('Module Icommercewompi: Confirmation - Order Status '.$order->status_id);

            // Status Order 'Proccesing'
            /*
            if($order->status_id==1){
                \Log::info('Module Icommercewompi: Response - Actualizando orderID: '.$orderID);

                // Default
                $newstatusOrder = 7; // Status Order Failed

                if($wompiTransaction->status=="APPROVED"){
                    $newstatusOrder = 13; // Status Order Processed
                }

                if($wompiTransaction->status=="DECLINED"){
                    $newstatusOrder = 5; // Status Order Denied
                }
                //Transacción anulada (sólo aplica pra transacciones con tarjeta)
                if($wompiTransaction->status=="VOIDED"){
                    $newstatusOrder = 12; // Status Order Voided
                }

                if($wompiTransaction->status=="ERROR"){
                    $newstatusOrder = 7; // Status Order Failed
                }

            }
            */

             // Update Transaction
            /*
            $transaction = $this->validateResponseApi(
                $this->transactionController->update($transactionID,new Request(
                    ["attributes" => [
                        'order_id' => $order->id,
                        'payment_method_id' => $paymentMethod->id,
                        'amount' => $order->total,
                        'status' => $newstatusOrder,
                        'external_status' => $external_status,
                        'external_code' => $external_code
                    ]
                ]))
            );

            // Update Order Process
            $orderUP = $this->validateResponseApi(
                $this->orderController->update($order->id,new Request(
                  ["attributes" =>[
                    'order_id' => $order->id,
                    'status_id' => $newstatusOrder
                  ]
                ]))
            );
            */
            

            \Log::info('Module Icommercewompi: Confirmation - END');

        } catch (\Exception $e) {

            //Log Error
            \Log::error('Module Icommercewompi: Message: '.$e->getMessage());
            \Log::error('Module Icommercewompi: Code: '.$e->getCode());

        }


        return response('Recibido', 200);

    }

   
}