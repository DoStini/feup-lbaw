@extends('layouts.logoOnlyApp')

@section('content')
<div class="container">
  <div class="row">
    <h1 class="text-center mb-4">Our Team</h1>
  </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="card">
              <img class="card-img-top" src="/img/dusto.jpeg" alt="Bologna">
              <div class="card-body text-center">
                <img class="avatar rounded-circle" src="/img/andre.png" width="50" height="50" alt="André Moreira">
                <h4 class="card-title">André Moreira</h4>
                <h6 class="card-subtitle mb-2 text-muted">Project Manager</h6>
                <p class="card-text"></p>
              </div>
              <div class="d-flex justify-content-evenly align-items-center flex-column">
                <i class="bi bi-github icon-click icon-link"></i>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
          <div class="card">
            <img class="card-img-top" src="/img/balta.jpg" alt="Bologna">
            <div class="card-body text-center">
              <img class="avatar rounded-circle" src="/img/balta.jpg" width="50" height="50" alt="Bologna">
              <h4 class="card-title">João Baltazar</h4>
              <h6 class="card-subtitle mb-2 text-muted">Project Slave</h6>
              <p class="card-text"></p>
            </div>
            <div class="d-flex justify-content-evenly align-items-center flex-column">
              <i class="bi bi-github"></i>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
          <div class="card">
            <img class="card-img-top" src="/img/nunoa.png" alt="Bologna">
            <div class="card-body text-center">
              <img class="avatar rounded-circle" src="/img/paypal.png" width="50" height="50" alt="Bologna">
              <h4 class="card-title">Nuno Alves</h4>
              <h6 class="card-subtitle mb-2 text-muted">Project Slave</h6>
              <p class="card-text"></p>
            </div>
            <div class="d-flex justify-content-evenly align-items-center flex-column">
              <i class="bi bi-github"></i>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
          <div class="card">
            <img class="card-img-top" src="/img/pendurado.jpeg" alt="Bologna">
            <div class="card-body text-center">
              <img class="avatar rounded-circle" src="/img/empreendedor.png" width="50" height="50" alt="Nuno Costa">
              <h4 class="card-title">Nuno Costa</h4>
              <h6 class="card-subtitle mb-2 text-muted">Chief Design Officer</h6>
              <p class="card-text"></p>
            </div>
            <div class="d-flex justify-content-evenly align-items-center flex-column">
              <i class="bi bi-github"></i>
            </div>
          </div>
        </div>
    </div>

</div>
@endsection