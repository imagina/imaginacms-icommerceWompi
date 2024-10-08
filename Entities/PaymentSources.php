<?php

namespace Modules\Icommercewompi\Entities;

use Modules\Core\Icrud\Entities\CrudModel;

class PaymentSources extends CrudModel
{
 
  protected $table = 'icommercewompi__payment_sources';
  public $transformer = 'Modules\Icommercewompi\Transformers\PaymentSourcesTransformer';
  public $repository = 'Modules\Icommercewompi\Repositories\PaymentSourcesRepository';
  public $requestValidation = [
      'create' => 'Modules\Icommercewompi\Http\Requests\CreatePaymentSourcesRequest',
      'update' => 'Modules\Icommercewompi\Http\Requests\UpdatePaymentSourcesRequest',
    ];
  //Instance external/internal events to dispatch with extraData
  public $dispatchesEventsWithBindings = [
    //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
    'created' => [],
    'creating' => [],
    'updated' => [],
    'updating' => [],
    'deleting' => [],
    'deleted' => []
  ];
 
  protected $fillable = [
    'user_id',
    'status',
    'default',
    'options'
  ];

  protected $singleFlaggableCombination = ['user_id'];

  protected $casts = [
    'options' => 'array'
  ];

  public function user()
  {
    $driver = config('asgard.user.config.driver');
    return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
  }

  public function setOptionsAttribute($value)
  {
    $this->attributes['options'] = json_encode($value);
  }

  public function getOptionsAttribute($value)
  {
    return json_decode($value);
  }

}
