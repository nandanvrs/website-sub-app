@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Websites') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <table id="table_website" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
                        <thead>
                            <tr role="row">
                                <th>{{ __('Website') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="subscribe_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_subscribe">
                    <div class="form-group row">
                        <label for="title" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus autocomplete="off">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title" class="col-md-3 col-form-label text-md-right">{{ __('Email') }}</label>

                        <div class="col-md-8">
                            <input id="email" type="email" class="form-control" name="email" required autocomplete="off">

                        </div>
                    </div>
                    <input type="hidden" value="" id="website_id" name="website_id" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="subscribe_now" type="button" class="btn btn-primary">Subscribe</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#table_website').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            'type': 'GET',
            "url": "{{ url('api/websites') }}",
            "data": function(data) {
                $.each(self.ajaxParams, function(key, value) {
                    data[key] = value;
                });
            },

        },
        "columns": [{
                "data": 'name',
                'sData': 'name'
            },
            {
                data: null,
                orderable: false,
                render: function(t, type, row) {
                    return '<a  class="btn subscribe btn-info" data-id="' + t.id + '">Subscribe</a>';
                }
            },
        ]
    });

    $(document).on('click', '.subscribe', function(e) {
        $('#subscribe_modal').modal('show');
        $('#website_id').val($(e.target).data('id'));
    });

    $(document).on('click', '#subscribe_now', function(e) {
        e.preventDefault();
        $('#subscribe_now').attr('disabled','disabled');
        $.ajax({
            'type': 'post',
            'url': '{{ url("api/subscribe") }}',
            'data': $('#form_subscribe').serialize(),
            'success': function(response) {
                $('#subscribe_now').removeAttr('disabled');
                toastr.success('Subscribed successfully.');
                $('#subscribe_modal').modal('hide');
                document.getElementById("form_subscribe").reset();
            },
            'error': function(error) {
                $('#subscribe_now').removeAttr('disabled');
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
                } else {
                    toastr.error(error.responseJSON.message);
                }
            }
        });
    });
</script>
@endpush