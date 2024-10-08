<?php

namespace Modules\Icommercewompi\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Icommerce\Entities\PaymentMethod;

class IcommercewompiDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($methodsFromOther=null)
    {
      
      Model::unguard();
  
      $this->call(IcommercewompiModuleTableSeeder::class);

      $methods = config('asgard.icommercewompi.config.methods');

      //Only the methods that match with the config
      if(!is_null($methodsFromOther))
            $methods = array_intersect_key($methods,$methodsFromOther);

      if(count($methods)>0){
        $init = "Modules\Icommercewompi\Http\Controllers\Api\IcommerceWompiApiController";
        foreach ($methods as $key => $method) {
          $result = PaymentMethod::where('name',$method['name'])->first();

          if(!$result){

              $options['init'] = $init;

              $options['mainimage'] = null;
              $options['publicKey'] = null;
              $options['privateKey'] = null;
              $options['eventSecretKey'] = null;
              $options['signatureIntegrityKey'] = null;
              $options['mode'] = "sandbox";
              $options['minimunAmount'] = 15000;
              $options['showInCurrencies'] = ["COP"];
              $options['paymentAttemps'] = 3;

              $titleTrans = $method['title'];
              $descriptionTrans = $method['description'];

              $params = array(
                  'name' => $method['name'],
                  'status' => $method['status'],
                  'options' => $options,
                  'organization_id' => isset(tenant()->id) ? tenant()->id : null
              );

              if(isset($method['parent_name']))
                  $params['parent_name'] = $method['parent_name'];
                

              $paymentMethod = PaymentMethod::create($params);

              $this->addTranslation($paymentMethod,'en',$titleTrans,$descriptionTrans);
              $this->addTranslation($paymentMethod,'es',$titleTrans,$descriptionTrans);
              

          }

        }
      }
   
    }


    /*
    * Add Translations
    * PD: New Alternative method due to problems with astronomic translatable
    **/
    public function addTranslation($paymentMethod,$locale,$title,$description){

      \DB::table('icommerce__payment_method_translations')->insert([
          'title' => trans($title,[],$locale),
          'description' => trans($description,[],$locale),
          'payment_method_id' => $paymentMethod->id,
          'locale' => $locale
      ]);

    }

}