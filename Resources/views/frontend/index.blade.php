@extends('layouts.master')

@section('title')
  Wompi | @parent
@stop


@section('content')
<div class="icommerce_wompi icommerce_wompi_index">
    <div class="container-lg">
      
     <h2 class="text-center">{{trans("icommercewompi::icommercewompis.title.welcome")}}</h2>
     <div class="my-5 py-1 justify-content-center">

      <div class="row">
        <div class="col-md-7 col-lg-8 mb-5">
          @include('icommercewompi::frontend.partials.list-methods')
          @include('icommercewompi::frontend.partials.add-methods')
        </div>
        <div class="col-md-5 col-lg-4 mb-5">
          @include('icommercewompi::frontend.partials.information')
        </div>
      </div>
     
     </div>
  
    </div>
</div>
@include('icommercewompi::frontend.partials.style')
@stop