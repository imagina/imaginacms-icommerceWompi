<div class="card">
    <h5 class="card-header bg-primary text-white">{{trans("icommercewompi::icommercewompis.title.payment resumen")}}</h5>
    <div class="card-body px-4">

        <ul class="list-group list-group-flush">
            <li class="list-group-item">{{trans("icommercewompi::icommercewompis.table.first name")}}: {{$order->first_name}}</li>
            <li class="list-group-item">{{trans("icommercewompi::icommercewompis.table.last name")}}: {{$order->last_name}}</li>
            <li class="list-group-item">Email: {{$order->email}}</li>

            @if(!is_null($order->shipping_method) && $order->require_shipping)

                @if($order->shipping_amount>0)
                    @php $subtotal = $order->total - $order->shipping_amount; @endphp
                    <li class="list-group-item">Subtotal: {{formatMoney($subtotal)}}</li>
                @endif

                <li class="list-group-item">{{trans('icommerce::order_summary.shipping')}}: {{$order->shipping_method}}</li>

                @if($order->shipping_amount>0)
                    <li class="list-group-item">{{trans("icommercewompi::icommercewompis.table.shipping amount")}}: {{formatMoney($order->shipping_amount)}}</li>
                @endif
            @endif

            <li class="list-group-item">
                <strong>Total: {{formatMoney($order->total)}} {{$order->currency_code}}</strong>
            </li>

            <li class="list-group-item text-center">
                <a id="btnPayWompi" class="btn btn-primary btn-sm text-uppercase" role="button" href="#" title="{{trans("icommercewompi::icommercewompis.messages.make payment")}}">
                    {{trans("icommercewompi::icommercewompis.button.pay")}}
                </a>
            </li>
        </ul>

    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

    //BTN | Event
    $('#btnPayWompi').on('click', function() {
       
        let psSelected = $('input[name=methodsRadio]:checked').val();

        if (typeof psSelected === 'undefined') {
            alert("{{trans('icommercewompi::icommercewompis.messages.select a payment method')}}")
        }else{
            let finalUrl = "{{$redirectUrl}}"+"/"+psSelected
            window.location.href = finalUrl;
        }
           
    }); 

});
</script>