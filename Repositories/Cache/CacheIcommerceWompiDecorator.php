<?php

namespace Modules\Icommercewompi\Repositories\Cache;

use Modules\Icommercewompi\Repositories\IcommerceWompiRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheIcommerceWompiDecorator extends BaseCacheDecorator implements IcommerceWompiRepository
{
    public function __construct(IcommerceWompiRepository $icommercewompi)
    {
        parent::__construct();
        $this->entityName = 'icommercewompi.icommercewompis';
        $this->repository = $icommercewompi;
    }

    
}