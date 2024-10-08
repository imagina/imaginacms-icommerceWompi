<?php

namespace Modules\Icommercewompi\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model
use Modules\Icommercewompi\Entities\PaymentSources;
use Modules\Icommercewompi\Repositories\PaymentSourcesRepository;

class PaymentSourcesApiController extends BaseCrudController
{
  public $model;
  public $modelRepository;

  public function __construct(PaymentSources $model, PaymentSourcesRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }
}
