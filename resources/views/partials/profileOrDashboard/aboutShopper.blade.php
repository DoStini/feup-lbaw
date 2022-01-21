<div>
    <h3>About {{$shopper->user->name}}</h3>
    <div id="shopper-info"></div>
</div>

<script>
    let val = @php echo json_encode($shopper->about_me) @endphp;
    const patt = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script\s*>/gi
    val =  val.replace(patt, '')

    document.getElementById('shopper-info').innerHTML = val;
</script>
