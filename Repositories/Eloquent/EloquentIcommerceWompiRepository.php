<?php

namespace Modules\Icommercewompi\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Icommercewompi\Repositories\IcommerceWompiRepository;

class EloquentIcommerceWompiRepository extends EloquentBaseRepository implements IcommerceWompiRepository
{
    /**
     * Calculates in Checkout
     */
    public function calculate($parameters, $conf)
    {
        $response['status'] = 'success';

        // Search Cart
        if (isset($parameters['cartId'])) {
            $cartRepository = app('Modules\Icommerce\Repositories\CartRepository');
            $cart = $cartRepository->find($parameters['cartId']);
        }

        // Validating Min Amount Order
        if (isset($conf->minimunAmount) && ! empty($conf->minimunAmount)) {
            if (isset($cart->total) || isset($parameters['total'])) {
                if (($cart->total ?? $parameters['total']) < $conf->minimunAmount) {
                    $response['status'] = 'error';
                    $response['msj'] = trans('icommerce::common.validation.minimumAmount', ['minimumAmount' => formatMoney($conf->minimunAmount)]);

                    return $response;
                }
            }
        }

        return $response;
    }
}
