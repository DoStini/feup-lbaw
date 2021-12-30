<div class="container h-100 my-3">
    <div class="row">
        <div class="col-md-3 col-sm-12 container">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="w-50">
                    @php
                        $photo_url = null;
                        if($admin) $photo_url = $admin->photo->url;
                        else $photo_url = $shopper->user->photo->url; 
                    @endphp
                    @if (File::exists(public_path($photo_url)))
                        <img id="user-img" src={{asset($photo_url)}} class="img-fluid" alt="" loading="lazy" />
                    @else
                        <img id="user-img" src="/img/user.png" class="img-fluid" alt="" loading="lazy" />
                    @endif
                </div>
                <div>
                    @if($admin)
                        <h3>{{$admin->name}}</h3>
                    @else
                        <h3>{{$shopper->user->name}}</h3>
                    @endif
                </div>
            </div>
            <div class="my-3 mx-2">
                @include('partials.links.' . $links, ['admin' => $admin ?? null, 'shopper' => $shopper ?? null])
            </div>
        </div>
        <div class="col-md-9 col-sm-12 container">
            @include('partials.' . $page, ['shopper' => $shopper ?? null, 'admin' => $admin ?? null, 'info' => $info ?? null])
        </div>
    </div>
</div>