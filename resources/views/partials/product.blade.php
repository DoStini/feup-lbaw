<article class="product" data-id="{{ $product->id }}">
    <h1>{{$product->name}}</h1>
    <h3> Stock </h3>
    <p>{{$product->stock}}</p>
    <h3> Desc </h3>
    <p>{{$product->description}}</p>
    <h3>Price</h3>
    <p> {{$product->price}} £</p>
    <h3>Rate</h3>
    <p> {{$product->avg_stars}}</p>
</article>