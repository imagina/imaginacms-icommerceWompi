<div class="card text-center list-methods mb-4">

    <h5 class="card-header bg-primary text-white">{{trans("icommercewompi::icommercewompis.title.payment methods")}}</h5>

    <div class="card-body">
      
      @if(count($userPaymentSources)>0)

        <p class="card-text">{{trans("icommercewompi::icommercewompis.title.select payment method")}}:</p>

        <table class="table">
          <thead class="thead-light">
            <tr>
              <th scope="col">{{trans("icommercewompi::icommercewompis.table.type")}}</th>
              <th scope="col">{{trans("icommercewompi::icommercewompis.table.last 4 digits")}}</th>
              <th scope="col">{{trans("icommercewompi::icommercewompis.table.title")}}</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($userPaymentSources as $item)
            <tr>
              <td>{{$item->options->type}}</td>
              <td>
                @if(isset($item->options->last_four)) {{$item->options->last_four}} @endif
                @if(isset($item->options->phone)) {{$item->options->phone}} @endif
              </td>
              <td> @if(isset($item->options->card_holder)) {{$item->options->card_holder}} @endif</td>
              <td>
                
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="methodsRadio" id="methodsRadio{{$item->id}}" value="{{icommercewompi_encriptPS($item->id)}}">
                  <label class="form-check-label" for="methodsRadio{{$item->id}}"></label>
                </div>

              </td>
            </tr>
            @endforeach
            
          </tbody>
        </table>
      @else
        <div class="alert alert-warning" role="alert">{{trans("icommercewompi::icommercewompis.messages.not payment method added")}}</div>
      @endif

    </div>
  </div>