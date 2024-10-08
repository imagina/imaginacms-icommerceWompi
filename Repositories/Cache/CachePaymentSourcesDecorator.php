<?php

namespace Modules\Icommercewompi\Repositories\Cache;

use Modules\Icommercewompi\Repositories\PaymentSourcesRepository;
use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;

class CachePaymentSourcesDecorator extends BaseCacheCrudDecorator implements PaymentSourcesRepository
{
    public function __construct(PaymentSourcesRepository $paymentsources)
    {
        parent::__construct();
        $this->entityName = 'icommercewompi.paymentsources';
        $this->repository = $paymentsources;
    }
}
