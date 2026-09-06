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
                  <li class="breadcrumb-item"><a href="#">Administration</a></li>
                  <li class="breadcrumb-item active" aria-current="page">User Management</li>
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
                            <h3 class="mb-0">User Creation</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                </div>
                <div class="pl-lg-4">
                                <ul id="saveform_errList"></ul>
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="input-name">{{ __('Username') }}</label>
                                    <input type="text" name="name" id="input-name" class="username form-control " >
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="input-name">{{ __('Email Address') }}</label>
                                    <input type="text" name="email" id="input-name"  class="email Address form-control ">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="input-name">{{ __('Country') }}</label>
                                    <input type="text" name="country" id="input-name"  class="country form-control ">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="input-name">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone number" id="input-name"  class="phone form-control ">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success add_user">{{ __('Create') }}</button>
                                </div>
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

@section('scripts')

<script>
$(document).ready(function(){
    $(document).on('click','.add_user',function (){
        e.preventDefault();
        var data = {
            'username': $('.username').val(),
            'email': $('.email').val(),
            'country': $('.country').val(),
            'phone number': $('.phone').val(),
        }

        $ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')attr('content')            
            }
        });
        $ajax({
            type: "POST",
            url: "/users",
            data: data,
            dataType: "json",
            success: function (response){
                if(response.status == 400)
                {
                    $('#saveform_errList').html("");
                    $('#saveform_errList').addClass('alert alert-danger');
                    $.each(response.errors, function (indexInArray, err_values){
                        $('#saveform_errList').append('<li>'+err_values+'</li>')
                    });
                }
                else
                {
                    $('#saveform_errList').html("");
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text(response.message);
                }
            }
        });
    });
});



</script>

@endsection
