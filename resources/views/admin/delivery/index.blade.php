@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Pedidos</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Pedidos</li>
	</ul>
</div>
<!--
@can('create-sale')
<div class="col-sm-5 col">
	<a href="{{route('sales.create')}}" class="btn btn-primary float-right mt-2">Agregar Venta</a>
</div>
@endcan
@endpush
-->
@section('content')
<div class="row">
	<div class="col-md-12">
	
		<!--  Deliverys -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="sales-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Nombre Cliente<th>
								<th>Cantidad de pedido</th>
								<th>Precio Total</th>
								<th>Estado Pedido</th>
								<th>Fecha Emision</th>
								<th>Fecha Despacho</th>
							<!--
								<th class="action-btn">Accion</th>
							-->
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- / sales -->
		
	</div>
</div>


@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('sales.index')}}",
            columns: [
                {data: 'product', name: 'product'},
                {data: 'user', name: 'user'},
                {data: 'quantity', name: 'quantity'},
                {data: 'total_price', name: 'total_price'},
                {data: 'status', name: 'status'},
				{data: 'date_up', name: 'date_up'},
				{data: 'date_down', name: 'date_down'},
            //    {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
    });
</script> 
@endpush