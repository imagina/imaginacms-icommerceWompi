@php
    $options = array('required' =>'required');
    $formID = uniqid("form_id");
@endphp

{!! Form::open(['route' => ['admin.icommerce.paymentmethod.update',$method->id], 'method' => 'put','name' => $formID]) !!}

<div class="col-xs-12 col-sm-9">

    <div class="row">

        <div class="nav-tabs-custom">
            @include('partials.form-tab-headers')
            <div class="tab-content">
                <?php $i = 0; ?>
                @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                    <?php $i++; ?>
                    <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="{{$method->name}}_tab_{{ $i }}">
                        
                        {!! Form::i18nInput('title', trans('icommerce::paymentmethods.table.title'), $errors, $locale, $method) !!}
                        {!! Form::i18nInput('description', trans('icommerce::paymentmethods.table.description'), $errors, $locale, $method) !!}
                    
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>

    <div class="row">
    <div class="col-xs-12">
        
        <div class="form-group ">
            <label for="merchantId">{{trans('icommercewompi::icommercewompis.table.merchantId')}}</label>
            <input placeholder="{{trans('icommercewompi::icommercewompis.table.merchantId')}}" required="required" name="merchantId" type="text" id="merchantId" class="form-control" value="{{$method->options->merchantId}}">
        </div>

        <div class="form-group ">
            <label for="apilogin">{{trans('icommercewompi::icommercewompis.table.apilogin')}}</label>
            <input placeholder="{{trans('icommercewompi::icommercewompis.table.apilogin')}}" required="required" name="apilogin" type="text" id="apilogin" class="form-control" value="{{$method->options->apilogin}}">
        </div>

        <div class="form-group ">
            <label for="apikey">{{trans('icommercewompi::icommercewompis.table.apiKey')}}</label>
            <input placeholder="{{trans('icommercewompi::icommercewompis.table.apiKey')}}" required="required" name="apiKey" type="text" id="apiKey" class="form-control" value="{{$method->options->apiKey}}">
        </div>

        <div class="form-group ">
            <label for="accountId">{{trans('icommercewompi::icommercewompis.table.accountId')}}</label>
            <input placeholder="{{trans('icommercewompi::icommercewompis.table.accountId')}}" required="required" name="accountId" type="text" id="accountId" class="form-control" value="{{$method->options->accountId}}">
        </div>

        <div class="form-group">
            <label for="mode">{{trans('icommercewompi::icommercewompis.table.mode')}}</label>
            <select class="form-control" id="mode" name="mode" required>
                    <option value="sandbox" @if(!empty($method->options->mode) && $method->options->mode=='sandbox') selected @endif>SANDBOX</option>
                    <option value="live" @if(!empty($method->options->mode) && $method->options->mode=='live') selected @endif>LIVE</option>
            </select>
        </div>

        <div class="form-group">
            <label for="test">*{{trans('icommercewompi::icommercewompis.table.test')}}</label>
            <select class="form-control" id="test" name="test" required>
                <option value="1" @if($method->options->test==1) selected @endif>YES</option>
                <option value="0" @if($method->options->test==0) selected @endif>NO</option>
            </select>
        </div>

        <div class="form-group">
            <div>
                <label class="checkbox-inline">
                    <input name="status" type="checkbox" @if($method->status==1) checked @endif>{{trans('icommerce::paymentmethods.table.activate')}}
                </label>
            </div>   
        </div>

    </div>
    </div>

</div>

<div class="col-sm-3">
    
    @include('icommercewompi::admin.icommercewompis.partials.featured-img',['crop' => 0,'name' => 'mainimage','action' => 'create'])
    
</div>
    
    
 <div class="clearfix"></div>   

    <div class="box-footer">
    <button type="submit" class="btn btn-primary btn-flat">{{ trans('icommerce::paymentmethods.button.save configuration') }} {{$method->title}}</button>
    </div>



{!! Form::close() !!}