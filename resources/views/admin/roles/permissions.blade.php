@extends('admin.layouts.app')

<x-assets.datatables />  

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Permisos</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Permisos</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#add_permission" data-toggle="modal" class="btn btn-primary float-right mt-2">Agregar Permiso</a>
</div>

@endpush

@section('content')

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="perm-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>Nombre</th>
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

<!-- Modal Agregar -->
<div class="modal fade" id="add_permission" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Agregar Permiso</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('permissions.store')}}">
					@csrf
					<div class="row form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Permiso</label>
								<input type="text" name="permission" class="form-control">
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Agregar -->

<!-- Modal Editar Detalles -->
<div class="modal fade" id="edit_permission" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Permiso</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('permissions.update')}}">
					@csrf
					@method("PUT")
					<div class="row form-row">
						<div class="col-12">
							<input type="hidden" name="id" id="edit_id">
							<div class="form-group">
								<label>Permiso</label>
								<input type="text" class="form-control perm_name" name="permission">
							</div>
						</div>
						
					</div>
					<button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Editar Detalles -->

@endsection


@push('page-js')
	<script>
		$(document).ready(function() {
            var table = $('#perm-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('permissions.index')}}",
                columns: [
                    {data: 'name', name: 'name'},
                    // {data: 'role', name: 'role'},
                    {data: 'created_at',name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
			$('#perm-table').on('click','.editbtn',function (){
				$('#edit_permission').modal('show');
				var id = $(this).data('id');
				var permission = $(this).data('name');
				$('#edit_id').val(id);
				$('.perm_name').val(permission);
			});
			//
		});
	</script>
	
@endpush
