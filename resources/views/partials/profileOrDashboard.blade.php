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
                <div class="w-100">
                    @if($admin)
                        <h3 id="name-profile" class="text-center" style="overflow-wrap: break-word;">{{$admin->name}}</h3>
                    @else
                        <h3 id="name-profile" class="text-center" style="overflow-wrap: break-word;">{{$shopper->user->name}}</h3>
                    @endif
                </div>
            </div>
            
            <div class="my-3 mx-2">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header menu-collapse-btn" id="flush-headingOne">
                        <button class="accordion-button collapsed bg-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                          Menu
                        </button>
                      </h2>
                      <div id="flush-collapseOne" class="accordion-collapse collapse dont-collapse-sm" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            @include('partials.links.' . $links, ['admin' => $admin ?? null, 'shopper' => $shopper ?? null])
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-12 container">
            @include('partials.profileOrDashboard.' . $page, 
            ['shopper' => $shopper ?? null,
             'admin' => $admin ?? null])
        </div>
    </div>
</div>