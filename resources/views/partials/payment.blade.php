<article class="payment" data-id="{{ $payment->id }}">
    <h1>Payment</h1>
    <h1>{{$payment->value}}</h1>
    <h3> Paypal </h3>
    <p>{{$payment->paypal_transaction_id}}</p>
    <h3> Entity </h3>
    <p>{{$payment->entity}}</p>
    <h3> Reference </h3>
    <p> {{$payment->reference}}</p>
</article>
