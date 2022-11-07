@extends('admin.layouts.plain')

@section('content')
<h1>Registro</h1>
<p class="account-subtitle">Acceder al Panel de Control</p>

<!-- Form -->
<form action="{{route('register')}}" method="POST">
	@csrf
	<div class="form-group">
		<input class="form-control" name="name" type="text" value="{{old('name')}}" placeholder="Nombre Completo">
	</div>
	<div class="form-group">
		<input class="form-control" name="email" type="text" value="{{old('email')}}" placeholder="Email">
	</div>
	
	<div class="form-group">
        <label>Departamento</label>
        <div class="form-group">
            <select class="select2 form-select form-control" name="location">
                <option value="Cochabamba">Cochabamba</option>
                <option value="La Paz">La Paz</option>
                <option value="Santa Cruz">Santa Cruz</option>
                <option value="Pando">Pando</option>
                <option value="Beni">Beni</option>
                <option value="Oruro">Oruro</option>
                <option value="Potosi">Potosi</option>
                <option value="Chuquisaca">Chuquisaca</option>
        	    <option value="Tarija">Tarija</option>
    	    </select>
        </div>
    </div>
    
	<div class="form-group">
		<input class="form-control" name="password" type="password" placeholder="Contraseña">
	</div>
	<div class="form-group">
		<input class="form-control" name="password_confirmation" type="password" placeholder="Confirmar Contraseña">
	</div>
	<div class="form-group mb-0">
		<button class="btn btn-primary btn-block" type="submit">Registrar</button>
	</div>
</form>
<!-- /Form -->
								
<div class="text-center dont-have">Ya tienes cuenta? <a href="{{route('login')}}">Ingresar</a></div>
@endsection