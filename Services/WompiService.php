<?php

namespace Modules\Icommercewompi\Services;

use Modules\Icommercewompi\Entities\Wompi as WompiEntity;

class WompiService
{

	public function __construct(){

	}

	public function create($paymentMethod,$order,$transaction,$urls){

		$wompi = new WompiEntity();

                $urlWompi = $paymentMethod->options->mode=="sandbox" ? $urls['sandbox'] : $urls['production'];

                $wompi->setUrlgate($urlWompi);
                $wompi->setPublicKey($paymentMethod->options->publicKey);
                $wompi->setReferenceCode(icommercewompi_getOrderRefCommerce($order,$transaction));
                $wompi->setAmount($order->total);
                $wompi->setCurrency($order->currency_code);
                $wompi->setRedirectUrl(Route("icommercewompi.response",$order->id));

                return $wompi;

	}

}