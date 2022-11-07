@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
	
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Productos</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Productos</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="{{route('products.create')}}" class="btn btn-primary float-right mt-2">Agregar Producto</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">
	
		<!-- Productos -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="product-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Nombre de Producto</th>
								<th>Categoria</th>
								<th>Precio</th>
								<th>Cantidad</th>
								<th>Descuento</th>
								<th>Fecha Expiracion</th>
								<th class="action-btn">Accion</th>
							</tr>
						</thead>
						<tbody>

														
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /Products -->
		
	</div>
</div>

@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('products.index')}}",
            columns: [
                {data: 'product', name: 'product'},
                {data: 'category', name: 'category'},
                {data: 'price', name: 'price'},
                {data: 'quantity', name: 'quantity'},
                {data: 'discount', name: 'discount'},
				{data: 'expiry_date', name: 'expiry_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
    });
</script> 
@endpush