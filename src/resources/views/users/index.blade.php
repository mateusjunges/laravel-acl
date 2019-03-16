@extends('acl::layouts.page')
@section('title', 'Lista de usuários')
@section('css')
    <link rel="stylesheet" href="">
@endsection
@section('js')
    <script src="{{ asset('/vendor/mateusjunges/acl/js/users/index.js') }}"></script>
    <script src="{{ asset('/vendor/mateusjunges/acl/js/general/general.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid">
       <div class="container">
           <h2 class="text-center">Lista de usuários do sistema</h2>
       </div>
        <div class="row">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
            <div class="col-md-12">
                <table class="table dataTable">
                    <thead class="">
                        <tr>
                            <th>Nome</th>
                            <th>Username</th>
                            @can('users.viewPermissions')
                                <th>Ver Permissões</th>
                            @endcan
                            @can('deniedPermissions.view')
                                <th>Permissões bloqueadas</th>
                            @endcan
                            @can('users.update')
                                <th>Editar</th>
                            @endcan
                            @can('users.delete')
                                <th>Remover</th>
                            @endcan
                            @can('roles.view')
                                <th>Ver Grupos</th>
                            @endcan
                            <th>Email</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection