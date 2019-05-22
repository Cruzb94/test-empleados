@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12"> 
            <div class="card">        
                <div class="card-header">Listado de Reportes</div> 
                <div class="card-body" style="display: inline-table">
                    <a href="{!!!!}" class="btn btn-primary" data-toggle="modal" data-target="#addReporteModal">Crear Reporte</a>
                    <hr>     
                    <table class="table table-stripped table-responsive" id="reportes" style="display: inline-table">
                        <thead>
                            <tr>
                                @if(Auth::user()->nivel == 'Administrador')
                                <th>Usuario</th>
                                @endif
                                <th>Descripcíon</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach( $reportes as $reporte)
                            <tr id="tr-{{$reporte->id}}">
                                @if(Auth::user()->nivel == 'Administrador')
                                 <td>{{ $reporte->user->name }}</td>
                                @endif
                                <td id="td_descripcion-{{$reporte->id}}">{{ $reporte->descripcion }}</td>
                                <td id="td_fecha-{{$reporte->id}}">{{ $reporte->created_at }}</td>
                                <td> <button data-toggle="modal" data-target="#editReporteModal" data-id="{{ $reporte->id }}" data-descripcion="{{ $reporte->descripcion }}" class="btn btn-warning btn-sm edit_reporte" data-toggle="tooltip" data-placement="top" title="Editar usuario"><i class="fas fa-user-edit"></i>Editar</button>
                                    <button href="" class="btn btn-danger btn-sm delete_reporte" data-id="{{ $reporte->id }}" data-toggle="tooltip" data-placement="top" title="Eliminar reporte"><i class="fas fa-user-times"></i>Borrar</button></td>
                           </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>      

<div class="modal fade" id="addReporteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear Reporte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @csrf

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-right" id="">{{ __('Descripción') }}</label>

                <div class="col-md-10">
                    <textarea id="new_descripcion" class="form-control" name="new_descripcion" cols="5"></textarea>
                    <input type="hidden" name="id_user" id="id_user" value="{{Auth::user()->id}}">
                    <input type="hidden" name="user_nivel" id="user_nivel" value="{{Auth::user()->nivel}}">
                    <input type="hidden" name="user_name" id="user_name" value="{{Auth::user()->name}}">
                </div>
            </div>

        <!-- fin -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary"  id="guardar_reporte">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editReporteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Reporte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @csrf

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-right" id="">{{ __('Descripción') }}</label>

                <div class="col-md-10">
                    <textarea id="edit_descripcion" class="form-control" name="edit_descripcion" cols="5"></textarea>
                    <input type="hidden" name="id_reporte" id="id_reporte" value="{{Auth::user()->id}}">
                </div>
            </div>

        <!-- fin -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary"  id="guardar_edit_reporte">Guardar</button>
      </div>
    </div>
  </div>
</div>    

@endsection   
@section('js')


<script>    
    $(document).ready(function() {

    $('#reportes').DataTable();

    $(document).on("click", "#guardar_reporte", function(){
        var descripcion=$('#new_descripcion').val();
        var id_user=$('#id_user').val();
        var nivel=$('#user_nivel').val();
        var name_user=$('#user_name').val();

        if(descripcion !== ''){
            $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/'); ?>/nuevoReporte",
                    data:{
                        descripcion: descripcion, id_user: id_user, _token: '{{csrf_token()}}'},
                  success: function(response){

                        if(response.status == 'correcto'){
                            $('#addReporteModal').modal('hide');

                            if(nivel == 'Administrador'){
                                $('#reportes').append('<tr id="tr-'+response.id+'">'+
                                '<td>' + name_user  + '</td>'+ 
                                '<td id="td_descripcion-'+response.id+'">' + descripcion + '</td>'+ 
                                '<td id="td_fecha-'+response.id+'">' + response.fecha + '</td>'+ 
                                '<td><button data-toggle="modal" data-target="#editReporteModal" class="btn btn-warning btn-sm edit_reporte" data-id="'+response.id+'" data-descripcion="'+descripcion+'" data-toggle="tooltip" data-placement="top" title="Editar reporte"><i class="fas fa-user-edit"></i>Editar</button><button href="" class="btn btn-danger btn-sm delete_reporte" data-id="'+response.id+'" data-toggle="tooltip" data-placement="top" title="Eliminar usuario"><i class="fas fa-user-times"></i>Borrar</button></td></tr>');
                            }else{
                                $('#reportes').append('<tr id="tr-'+response.id+'">'+
                                '<td id="td_descripcion-'+response.id+'">' + descripcion + '</td>'+ 
                                '<td id="td_fecha-'+response.id+'">' + response.fecha + '</td>'+ 
                                '<td><button data-toggle="modal" data-target="#editReporteModal" class="btn btn-warning btn-sm edit_reporte" data-id="'+response.id+'" data-descripcion="'+descripcion+'" data-toggle="tooltip" data-placement="top" title="Editar reporte"><i class="fas fa-user-edit"></i>Editar</button><button href="" class="btn btn-danger btn-sm delete_reporte" data-id="'+response.id+'" data-toggle="tooltip" data-placement="top" title="Eliminar usuario"><i class="fas fa-user-times"></i>Borrar</button></td></tr>');
                            }

                            



                            alert('Reporte registrado con éxito');

                            $('#new_descripcion').val('');
                        }
                    }                   
                }); 
        }else{
            alert('Debe rellenar todos los campos');
        }
    });

     $(document).on("click", ".edit_reporte", function(){
        $('#edit_descripcion').val('');
        $('#id_reporte').val('');

        var descripcion=$(this).data('descripcion');
        var reporte=$(this).data('id');

        $('#edit_descripcion').val(descripcion);
        $('#id_reporte').val(reporte);
    });

    $(document).on("click", "#guardar_edit_reporte", function(){
        var descripcion=$('#edit_descripcion').val();
        var id_reporte=$('#id_reporte').val();

        console.log(descripcion, id_reporte);

        if(descripcion !== ''){
            $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/'); ?>/editarReporte",
                    data:{
                        descripcion: descripcion, id_reporte: id_reporte, _token: '{{csrf_token()}}'},
                  success: function(response){

                        if(response.status == 'correcto'){
                            $('#editReporteModal').modal('hide');

                            $('#td_descripcion-'+id_reporte).empty();
                            $('#td_fecha-'+id_reporte).empty();

                            $('#td_descripcion-'+id_reporte).append(descripcion);
                            $('#td_fecha-'+id_reporte).append(response.fecha);

                            alert('Reporte actualizado con éxito');

                            $('#edit_descripcion').val('');
                        }
                    }                   
                }); 
        }else{
            alert('Debe rellenar todos los campos');
        }
    });

    $(document).on("click", ".delete_reporte", function(){
        var id_reporte=$(this).data('id');

        var r = confirm("¿Desea eliminar este reporte?");
        if (r == true) {
           $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/'); ?>/eliminarReporte",
                    data:{
                        id_reporte: id_reporte, _token: '{{csrf_token()}}'},
                  success: function(response){

                        if(response.status == 'correcto'){
                            
                            $('#tr-'+id_reporte).remove();
                            alert('Reporte eliminado');

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