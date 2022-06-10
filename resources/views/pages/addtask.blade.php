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
                  <li class="breadcrumb-item"><a href="{{ route('tasks') }}">Tasks</a></li>
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
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Task Creation</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                </div>
                <div class="pl-lg-4">
                   <form action = "addtask" method = "post">
                       @csrf
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control " >
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="input-name">{{ __('To Do') }}</label>
                                    <textarea type="text" name="todo" id="input-name"  class="type form-control "></textarea>
                                </div>
                            </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Create') }}</button>
                                </div>
                   </form>            
                </div>

                
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

        @include('layouts.footers.auth')
</div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush