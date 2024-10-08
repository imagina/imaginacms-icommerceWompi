<div class="card text-center add-methods">

    <h5 class="card-header bg-primary text-white">{{trans("icommercewompi::icommercewompis.title.add payment method")}}</h5>
    <div class="card-body">
      
      <p class="card-text">{{trans("icommercewompi::icommercewompis.messages.click to add method")}}</p>

      <form method="POST" action="{{route('icommercewompi.api.wompipaymentsources.processToken',['eUrl'=>$eURL])}}">
        <script
          src="https://checkout.wompi.co/widget.js"
          data-render="button"
          data-widget-operation="tokenize"
          data-public-key="{{$publicKey}}"
        ></script>
      </form>

    </div>

</div>