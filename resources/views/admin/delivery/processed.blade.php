@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Pedidos</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Procesado de Pedidos</li>
	</ul>
</div>
@endpush
@section('content')
<div class="row">
	<div class="col-md-12">
	
		<!--  Pedido Procesado -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="sales-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Id</th>
								<th>Dia</th>
								<th>Hora</th>
								<th>Pedido x Hr</th>
								<th>Cantidad x Hr</th>
								<th>Procesado x Hr</th>
								<th>Avance</th>
								<th>Trab x Hr</th>
								<th>Salario</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- / Pedido Procesado-->
		
	</div>
</div>


@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('delivery.processed')}}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'day', name: 'day'},
                {data: 'time', name: 'time'},
                {data: 'deliveriesPH', name: 'deliveriesPH'},
                {data: 'quantityPH', name: 'quantityPH'},
                {data: 'solvedPH', name: 'solvedPH'},
                {data: 'progressPH', name: 'progressPH'},
                {data: 'workersPH', name: 'workersPH'},
                {data: 'salaryPH', name: 'salaryPH'},
            ]
        });
        
    });
</script> 
@endpush