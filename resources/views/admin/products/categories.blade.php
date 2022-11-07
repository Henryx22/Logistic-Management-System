@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Categorias</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Categorias</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#add_categories" data-toggle="modal" class="btn btn-primary float-right mt-2">Agregar Categoria</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="category-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
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
<div class="modal fade" id="add_categories" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Agregar Categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('categories.store')}}">
					@csrf
					<div class="row form-row">
						<div class="col-12">
							<div class="form-group">
								<label>Categoria</label>
								<input type="text" name="name" class="form-control">
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

<!-- Modal Editar detalles -->
<div class="modal fade" id="edit_category" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('categories.update')}}">
					@csrf
					@method("PUT")
					<div class="row form-row">
						<div class="col-12">
							<input type="hidden" name="id" id="edit_id">
							<div class="form-group">
								<label>Categoria</label>
								<input type="text" class="form-control edit_name" name="name">
							</div>
						</div>
						
					</div>
					<button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Editar detalles --> 
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('categories.index')}}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'created_at',name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#category-table').on('click','.editbtn',function (){
            $('#edit_category').modal('show');
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#edit_id').val(id);
            $('.edit_name').val(name);
        });
        //
    });
</script> 
@endpush