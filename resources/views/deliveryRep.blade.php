<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ucfirst(AppSettings::get('app_name', 'App'))}} - {{ucfirst($title ?? '')}}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{!empty(AppSettings::get('favicon')) ? asset('storage/'.AppSettings::get('favicon')) : asset('assets/img/favicon.png')}}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/feathericon.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/icons.min.css')}}">
    <!-- Snackbar CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/snackbar/snackbar.min.css')}}">
    <!-- Sweet Alert css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2/sweetalert2.min.css')}}">
    <!-- Snackbar Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/snackbar/snackbar.min.css')}}">
    <!-- Select2 Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <!-- Page CSS -->
    @stack('page-css')
    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<a href="{{ route('ini') }}" class="btn btn-danger float-right mt-2">Regresar</a>
    
<div class="col-sm-5 col">
        <h3 class="float-right mt-2">Reporte de Procesado de Pedidos</h3>
</div>
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <!-- Page Wrapper -->
        <div class="login-wrapper">

            <div class="container">
                
                @yield('content')
                <div class="row">
                    <div class="col-md-12">
                    
                        @isset($deliveries)
                            <!--  Reporte de Procesado de pedidos -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="deliveries-table" class="datatable table table-hover table-center mb-0">
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
                                                @foreach($deliveries as $delivery)
                                                    @if (!(empty($delivery)))   
                                                        <tr>
                                                            <td>{{$delivery->id}}</td>
                                                            <td>{{$delivery->day}}</td>
                                                            <td>{{$delivery->time}}</td>
                                                            <td>{{$delivery->deliveriesPH}}</td>
                                                            <td>{{$delivery->quantityPH}}</td>
                                                            <td>{{$delivery->solvedPH}}</td>
                                                            <td>{{$delivery->progressPH}}</td>
                                                            <td>{{$delivery->workersPH}}</td>
                                                            <td>{{$delivery->salaryPH}}</td>
                                                            
                                                        </tr>
                                                    @endif  
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- / Reporte de procesado de pedidos -->
                        @endisset
                       
                        
                    </div>
                </div>

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


                <!-- add sales modal-->
                <x-modals.add-sale />
                 <!-- / add sales modal -->


            </div>
        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    
</body>
<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap Core JS -->
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- Sweet Alert Js -->
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Snackbar Js -->
<script src="{{asset('assets/plugins/snackbar/snackbar.min.js')}}"></script>
<!-- Select2 JS -->
<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Custom JS -->
<script src="{{asset('assets/js/script.js')}}"></script>
<script>
    $(document).ready(function(){
        $('body').on('click','#deletebtn',function(){
            var id = $(this).data('id');
            var route = $(this).data('route');
            swal.queue([
                {
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: '<i class="fe fe-trash mr-1"></i> Delete!',
                    cancelButtonText: '<i class="fa fa-times mr-1"></i> Cancel!',
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1,
                    preConfirm: function(){
                        return new Promise(function(){
                            $.ajax({
                                url: route,
                                type: "DELETE",
                                data: {"id": id},
                                success: function(){
                                    swal.insertQueueStep(
                                        Swal.fire({
                                            title: "Deleted!",
                                            text: "Resource has been deleted.",
                                            type: "success",
                                            showConfirmButton: !1,
                                            timer: 1500,
                                        })
                                    )
                                    $('.datatable').DataTable().ajax.reload();
                                }
                            })

                        })
                    }
                }
            ]).catch(swal.noop);
        }); 
    });
    @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type){
            case 'info':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    actionTextColor: '#fff',
                    backgroundColor: '#2196f3'
                });
                break;

            case 'warning':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e2a03f'
                });
                break;

            case 'success':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#8dbf42'
                });
                break;

            case 'danger':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
                break;
        }
    @endif
</script>

<!-- Page JS -->
@stack('page-js')
</html>