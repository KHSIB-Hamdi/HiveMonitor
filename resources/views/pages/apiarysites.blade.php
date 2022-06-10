@extends('layouts.app')

@section('content')
    @include('layouts.navbars.topnav')
    @include('layouts.headers.topheader')

    <div class="header bg-neutral pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home text-gray-dark"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{ route('apiarysites') }}">Apiary Sites</a></li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-6 col-5 text-right">
              
              <a href="#" class="btn btn-sm btn-neutral">Filters</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    
<div class="container-fluid mt--6">
    
    <div class="row">
        <div class="col">
          <div class="card border-0">
            <div id="map-default" class="map-canvas" data-lat="40.748817" data-lng="-73.985428" style="height: 600px;"></div>
          </div>
        </div>
    </div>

        @include('layouts.footers.auth')
</div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
@endpush