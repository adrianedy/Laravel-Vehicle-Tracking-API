@extends('layouts.admin')

@section('head')
<title>User Devices - MobiGPS</title>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<section class="section">
    <div class="section-header row">
        <div class="col-md-6 p-0">
            <h1>Devices</h1>
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.home') }}">Users</a></div>
            <div class="breadcrumb-item active">User Devices</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-sm-6">
                            <h4>Devices</h4>
                        </div>
                        {{-- <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-device"
                            data-backdrop="static"><i class="fa fa-plus" aria-hidden="true"></i> Add Device</button>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        @if (session('device'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>×</span>
                                    </button>
                                    {{ session('device') }}
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table id="devices-datatable" class="table table-striped table-md" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Device ID</th>
                                        <th>Name</th>
                                        <th>License</th>
                                        <th>Type</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('admin.modals.revoke')
@endsection

@section('script')
<script src="{{ asset('admin-assets/js/modal-script.js') }}?v={{ config('scriptversion.modal-script') }}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

<script>
$(document).ready(function () {
    $('#devices-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.devices.index', $user->id) }}",
        columns: [
            {data: 'device_id'},
            {data: 'name'},
            {data: 'license'},
            {data: 'type'},
            {data: 'created_at'},
            {data: 'action', orderable: false},
        ],
        columnDefs: [
            { width: '40px', targets: 5 }
        ],
        order: [[4, 'desc']],
        fnDrawCallback: function(){
            $('[data-toggle="tooltip"]').tooltip()
        }
    })
})
</script>
@endsection
