@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Posts') }} <a  href="{{ route('post.create') }}" class="btn btn-primary float-right">Create</a></div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <table id="table_post" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
                        <thead>
                            <tr role="row">
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Website') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#table_post').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            'type': 'GET',
            "url": "{{ url('api/posts') }}",
            "data": function(data) {
                $.each(self.ajaxParams, function(key, value) {
                    data[key] = value;
                });
            },
            "beforeSend": function(xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + '{{Auth::user()->createToken("api-token")->accessToken }}');
            },
        },
        "columns": [{
                "data": 'title',
                'sData': 'title'
            },
            {
                "data": 'name',
                'sData': 'name'
            },
            {
                data: null,
                orderable: false,
                render: function(t, type, row) {
                    return t.status;
                }
            },
        ]
    });
</script>
@endpush