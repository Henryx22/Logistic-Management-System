@extends('admin.layouts.app')

<x-assets.datatables />  

@push('page-css')
	
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Usuarios</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Usuarios</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="{{route('users.create')}}" class="btn btn-primary float-right mt-2">Agregar Usuario</a>
</div>

@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="user-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>Nombre</th>
								<th>Email</th>
								<th>Rol</th>
								<th>Avatar</th>
								<th>Fecha de Creacion</th>
								<th class="text-center action-btn">Acciones</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('page-js')
<script>
$(document).ready(function() {
    var table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('users.index')}}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
			{data: 'avatar', name: 'avatar', orderable: false, searchable: false},
            {data: 'created_at',name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
});
</script>
@endpush