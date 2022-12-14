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

    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <!-- Page Wrapper -->
        <div class="login-wrapper">

            <div class="container">
                
                @yield('content')
                <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        
                    @if (Route::has('login'))
                        <div class="hidden fixed top-0 rigth-0 px-6 py-4 sm:block">
                            @auth
                                <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary">Home</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
                <div class="loginbox">
                                    
                        <img class="login-left img-fluid" src="@if(!empty(AppSettings::get('logo'))) {{asset('storage/'.AppSettings::get('logo'))}} @else{{asset('assets/img/logo-welcome.png')}} @endif" alt="Logo">
                        
                    
                    <div class="login-rigth">
                                    <div class="p-2">
                                        <div class="flex items-center">
                                            
                                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="#generate_sale" data-toggle="modal" class="btn btn-primary ">Generar Ventas</a></div>
                                            
                                        </div>

                                        <div class="ml-6">
                                            <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                                Generar Ventas aleatorias automaticamente
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2">
                                        <div class="flex items-center">
                                            
                                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('procSale') }}" class="btn btn-primary ">Procesar Pedidos</a></div>
                                        </div>

                                        <div class="ml-6">
                                            <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                                Generar la cantidad de personal requerido para el procesamiento de los pedidos
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2">
                                        <div class="flex items-center">
                                            
                                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('delivRep') }}" class="btn btn-primary ">Generar Reporte</a></div>
                                        </div>

                                        <div class="ml-6">
                                            <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                                Reporte de personal destinado para procesamiento
                                            </div>
                                        </div>
                                    </div>    
                    </div>
                        
                </div>
                
                <div class="modal fade" id="generate_sale" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Generar Ventas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="{{ route('genSale') }}">
                                    @csrf
                                    <div class="row form-row">
                                        <div class="col-12">
                                            <div class="row">
                                                                    
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Numero de dias a simular </label>
                                                        <input type="number" name="numDays" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block submit_report">Enviar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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