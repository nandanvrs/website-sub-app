@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Creat Post') }} <a href="{{ route('home') }}" class="btn btn-primary float-right">View Posts</a></div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form id="form_post">
                        @csrf

                        <div class="form-group row">
                            <label for="website_id" class="col-md-4 col-form-label text-md-right">{{ __('Website') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="website_id">
                                    <option value="">select website</option>
                                    @foreach($websites as $web)
                                    <option value="{{$web->id}}">{{$web->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" name="description" class="form-control" rows="6" ></textarea>


                            </div>
                        </div>
                        <input type="hidden" name="status" value="published" />

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" id="publish_post">
                                    {{ __('Publish Post') }}
                                </button>
                            </div>
                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var btnPublish = document.getElementById('publish_post');
    btnPublish.addEventListener('click', function(e) {
        e.preventDefault();
        btnPublish.disabled = true;
        $.ajax({
            'type': 'post',
            'url': '{{ url("api/post/store") }}',
            'data': $('#form_post').serialize(),
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + '{{Auth::user()->createToken("api-token")->accessToken }}');
            },
            'success': function(response) {
                btnPublish.disabled = false;
                toastr.success('Post created successfully.');
                window.location = "{{route('home')}}"
            },
            'error': function(error) {
                btnPublish.disabled = false;
                if (error.status == 422 && error.responseJSON.hasOwnProperty('errors')) {
                    var errors = error.responseJSON.errors;
                    $.each(errors, function(key, value) {

                        // Try to get element from error key
                        var $el = $('[name="' + key + '"]');
                        if ($el.length) {
                            $el.closest('.form-group').addClass('has-error');
                            $el.addClass('is-invalid');
                            // Check for existing error label
                            if ($el.closest('.form-group').find('.invalid-feedback').length) {
                                $el.closest('.form-group').find('.invalid-feedback').html(value);
                            } else {
                                // if no error label is defined then create new one and append to it
                                var divErrorBox = $('<div>', {
                                    'class': 'error invalid-feedback',
                                    'name': key,
                                }).html(value);
                                $el.parent().append(divErrorBox)
                            }
                            $el.on('keyup change', function() {
                                if ($(this).val().trim().length > 0) {
                                    $(this).removeClass('is-invalid');
                                    $(this).closest('.form-group').removeClass('has-error');
                                    $(this).closest('.form-group').find('.invalid-feedback').html('');
                                    $(this).unbind('keyup');
                                }
                            })
                        }
                    });
                }else{
                    toastr.error(error.responseJSON.message);
                }
            }
        });

    });
</script>
@endpush