@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12"> 
            <div class="card">        
                @if(Auth::user()->nivel == 'Administrador')
                <div class="card-header">Listado de Empleados</div>
                @endif
                <div class="card-body" style="display: inline-table">
                    @if(Auth::user()->nivel == 'Administrador')
                    <button href="" class="btn btn-primary" data-toggle="modal" data-target="#addUsuarioModal">
  Registrar Empleado</button>
                    @endif
                    <hr>     
                    <table class="table table-stripped table-responsive" id="empleados" style="display: inline-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $users as $user)
                            <tr id="tr-{{$user->id}}">
                           		<td>{{ $user->name }}</td>
                           		<td>{{ $user->email }}</td>
                           		<td> <button data-toggle="modal" data-target="#editUsuarioModal" data-id="{{ $user->id }}" data-email="{{ $user->email }}" data-name="{{ $user->name }}" class="btn btn-warning btn-sm edit_user" data-toggle="tooltip" data-placement="top" title="Editar usuario"><i class="fas fa-user-edit"></i>Editar</button>
                                    <button href="" class="btn btn-danger btn-sm delete_user" data-id="{{ $user->id }}" data-toggle="tooltip" data-placement="top" title="Eliminar usuario"><i class="fas fa-user-times"></i>Borrar</button></td>
                           </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>     

<div class="modal fade" id="addUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Empleado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @csrf

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right" id="">{{ __('Nombre') }}</label>

                <div class="col-md-6">
                    <input id="new_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right" id="">{{ __('Correo') }}</label>

                <div class="col-md-6">
                    <input id="new_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right" >{{ __('Contraseña') }}</label>

                <div class="col-md-6">
                    <input id="new_password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                </div>
            </div>

        <!-- fin -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="guardar_usuario_cerrar" >Cerrar</button>
        <button type="button" class="btn btn-primary"  id="guardar_usuario">Guardar</button>
      </div>
    </div>
  </div>
</div> 

<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Empleado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @csrf

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right" id="">{{ __('Nombre') }}</label>

                <div class="col-md-6">
                    <input id="edit_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    <input type="hidden" name="id_user" id="id_user">
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right" id="">{{ __('Correo') }}</label>

                <div class="col-md-6">
                    <input id="edit_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                </div>
            </div>

        <!-- fin -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="guardar_usuario_cerrar" >Cerrar</button>
        <button type="button" class="btn btn-primary"  id="guardar_edit_usuario">Guardar</button>
      </div>
    </div>
  </div>
</div>    



@endsection
@section('js')


<script>    
    $(document).ready(function() {
    $('#empleados').DataTable();

    $(document).on("click", "#guardar_usuario", function(){
        var nombre=$('#new_name').val();
        var email=$('#new_email').val();
        var password=$('#new_password').val();

        if(nombre !== '' && email !== '' && password !== ''){
            $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/'); ?>/nuevoUsuario",
                    data:{
                        nombre: nombre, email: email, password: password, _token: '{{csrf_token()}}'},
                  success: function(response){

                        if(response.status == 'correcto'){
                            $('#addUsuarioModal').modal('hide');

                            $('#empleados').append('<tr id="tr-'+response.id+'">'+
                                '<td>' + nombre + '</td>'+ 
                                '<td>' + email + '</td>'+ 
                                '<td><button data-toggle="modal" data-target="#editUsuarioModal" class="btn btn-warning btn-sm edit_user" data-id="'+response.id+'" data-email="'+email+'" data-name="'+nombre+'" data-toggle="tooltip" data-placement="top" title="Editar usuario"><i class="fas fa-user-edit"></i>Editar</button><button href="" class="btn btn-danger btn-sm delete_user" data-toggle="tooltip" data-placement="top" title="Eliminar usuario"><i class="fas fa-user-times"></i>Borrar</button></td></tr>');

                            alert('Empleado registrado con éxito');

                            $('#new_name').val('');
                            $('#new_email').val('');
                            $('#new_password').val('');

                        }else if(response.status == 'error'){
                            alert('Empleado ya se encuentra registrado');
                            $('#new_name').val('');
                            $('#new_email').val('');
                            $('#new_password').val('');
                        }
                    }                   
                }); 
        }else{
            alert('Debe rellenar todos los campos');
        }
    });

    $(document).on("click", ".edit_user", function(){
        $('#edit_name').val('');
        $('#edit_email').val('');
        $('#id_user').val('');

        var nombre=$(this).data('name');
        var email=$(this).data('email');
        var id_user=$(this).data('id');

        $('#edit_name').val(nombre);
        $('#edit_email').val(email);
        $('#id_user').val(id_user);
    });
    

    $(document).on("click", "#guardar_edit_usuario", function(){


        var nombre=$('#edit_name').val();
        var email=$('#edit_email').val();
        var id_user=$('#id_user').val();

        if(nombre !== '' && email !== ''){
            $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/'); ?>/editarUsuario",
                    data:{
                        nombre: nombre, email: email, id_user: id_user, _token: '{{csrf_token()}}'},
                  success: function(response){

                        if(response.status == 'correcto'){
                            $('#editUsuarioModal').modal('hide');
                            $('#tr-'+id_user).empty();
                            $('#tr-'+id_user).append(
                                '<td>' + nombre + '</td>'+ 
                                '<td>' + email + '</td>'+ 
                                '<td><button href="" class="btn btn-warning btn-sm edit_user" data-id="'+id_user+'" data-email="'+email+'" data-name="'+nombre+'" data-toggle="tooltip" data-placement="top" title="Editar usuario"><i class="fas fa-user-edit"></i>Editar</button><button href="" class="btn btn-danger btn-sm delete_user" data-toggle="tooltip" data-placement="top" title="Eliminar usuario"><i class="fas fa-user-times"></i>Borrar</button></td>');

                            
                            alert('Empleado actualizado con éxito');


                        }else if(response.status == 'error'){
                            alert('Empleado ya se encuentra registrado');
                        }
                    }                   
                }); 
        }else{
            alert('Debe rellenar todos los campos');
        }
    });

     $(document).on("click", ".delete_user", function(){
        var id_user=$(this).data('id');

        var r = confirm("¿Desea eliminar este usuario?");
        if (r == true) {
           $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/'); ?>/eliminarUsuario",
                    data:{
                        id_user: id_user, _token: '{{csrf_token()}}'},
                  success: function(response){

                        if(response.status == 'correcto'){
                            
                            $('#tr-'+id_user).remove();
                            alert('Usuario eliminado');

                        }else{
                            alert('Error');
                        }
                    }                   
                }); 
        } else {
            return false;
        }
    });
});


</script>
@endsection