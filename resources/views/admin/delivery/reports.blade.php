@extends('admin.layouts.app')

<x-assets.datatables />


@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Reporte de Procesado de Pedidos</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Panel de Control</a></li>
		<li class="breadcrumb-item active">Generar Reporte de Procesado de Pedidos</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#generate_report" data-toggle="modal" class="btn btn-primary float-right mt-2">Generar Reporte</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">
	
		@isset($deliveries)
            <!--  Reporte de Ventas -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sales-table" class="datatable table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                	<th>Id</th>
									<th>Dia</th>
									<th>Hora</th>
									<th>Pedidos x Hora</th>
									<th>Cantidad x Hora</th>
									<th>Procesado x Hora</th>
									<th>Avance</th>
									<th>Trabajadores x Hora</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveries as $de)
                                <!--    @if (!(empty($de->product->purchase)))	-->
                                        <tr>
                                            <td>{{$de->id}}</td>
                                            <td>{{$de->day}}</td>
                                            <td>{{$de->time}}</td>
                                            <td>{{$de->deliveriesPH}}</td>
                                            <td>{{$de->quantityPH}}</td>
                                            <td>{{$de->solvedPH}}</td>
                                            <td>{{$de->progressPH}}</td>
                                            <td>{{$de->workersPH}}</td>
                                            
                                        </tr>
                                <!--    @endif	-->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- / Reporte de Ventas -->
        @endisset
       
		
	</div>
</div>

<!-- Generar Modal -->
<div class="modal fade" id="generate_report" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Generar Reporte</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('delivery.report')}}">
					@csrf
				<!--	
					<div class="row form-row">
						<div class="col-12">
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>De </label>
										<input type="date" name="from_date" class="form-control from_date">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>A </label>
										<input type="date" name="to_date" class="form-control to_date">
									</div>
								</div>
							</div>
						</div>
					</div>
				-->
					<button type="submit" class="btn btn-primary btn-block submit_report">Enviar</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Generar Modal -->
@endsection

@push('page-js')
<script>
    $(document).ready(function(){
        $('#deliveries-table').DataTable({
			dom: 'Bfrtip',		
			buttons: [
				{
				extend: 'collection',
				text: 'Export Data',
				buttons: [
					{
						extend: 'pdf',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					},
					{
						extend: 'excel',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					},
					{
						extend: 'csv',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					},
					{
						extend: 'print',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					}
				]
				}
			]
		});
    });
</script>
@endpush