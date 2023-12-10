@extends('Layout.layout')

@section('title','Checkout')

@section('style')
<script src="https://js.stripe.com/v3/"></script>

@endsection
@section('content')

<div clas="container">
<div class="row d-flex flex-wrap align-items-center justify-content-center" style="min-height: 400px">

    <section class="col-md-6 pb-4">

<h3 class="text-center my-4">Checkout</h3>
        <div id="card-payment-form">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                       Error try again.
                    @endforeach
                </ul>
            </div>
        @endif
            <form id="payment-form" class="text-center" method="post" action="{{route('charge.now')}}">

                @csrf
                <input type="text" hidden name="price" value="300">
                <input type="text" hidden name="post1" value="{{$post1}}">
                <input type="text" hidden name="post2" value="{{$post2}}">
                <input type="text" hidden name="table" value="{{$table}}">
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label for="card-element">Card Information</label>
                        <div id="card-element" class="form-control"></div>
                        <div id="card-errors" role="alert" class="invalid-feedback"></div>
                    </div>
                </div>
                <button class="btn btn-primary mx-auto" id="paybycard" type="submit">Pay Now 300 RS</button>
            </form>
         </div>
    </section>
</div>

</div>

@endsection

@section('script')
<script>

  // Create a Stripe client.
  var stripe = Stripe('{{env("STRIPE_KEY")}}');
   const elements = stripe.elements();
   // Custom styling can be passed to options when creating an Element.

   const style = {
   base: {
   fontSize: '16px',
   fontSmoothing: 'antialiased',
   color: '#495057',
   '::placeholder': {
     color: '#6c757d',
   },
   lineHeight: '1.5',
   },
   invalid: {
   color: '#dc3545',
   iconColor: '#dc3545',
   },
   };


   // Create an instance of the card Element.
   const card = elements.create('card', {style});

   // Add an instance of the card Element into the `card-element` <div>.
   card.mount('#card-element');

   // Create a token or display an error when the form is submitted.
   const form = document.getElementById('payment-form');
   const submitBtn = document.getElementById('paybycard');
   submitBtn.addEventListener('click', async (event) => {
   event.preventDefault();

   const {token, error} = await stripe.createToken(card);

   if (error) {
   // Inform the customer that there was an error.
   const errorElement = document.getElementById('card-errors');
   errorElement.textContent = error.message;
   } else {
   // Send the token to your server.
   stripeTokenHandler(token);
   }
   });

   const stripeTokenHandler = (token) => {
   // Insert the token ID into the form so it gets submitted to the server
   const form = document.getElementById('payment-form');
   const hiddenInput = document.createElement('input');
   hiddenInput.setAttribute('type', 'hidden');
   hiddenInput.setAttribute('name', 'stripeToken');
   hiddenInput.setAttribute('value', token.id);
   form.appendChild(hiddenInput);

   // Submit the form
   form.submit();
   }
</script>
@endsection