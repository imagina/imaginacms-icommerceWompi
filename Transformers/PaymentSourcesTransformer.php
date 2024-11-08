<?php

namespace Modules\Icommercewompi\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;

class PaymentSourcesTransformer extends CrudResource
{
  /**
  * Method to merge values with response
  *
  * @return array
  */
  public function modelAttributes($request)
  {
    return [];
  }
}
