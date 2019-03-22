@extends('acl::layouts.page')
@section('title', 'Permissões do usuário '.$user->name)
@section('js')
    <script src="{{ asset('vendor/mateusjunges/acl/js/users/permissions.js') }}"></script>
    <script src="{{ asset('vendor/mateusjunges/acl/js/general/general.js') }}"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Lista de permisões</h2>
            </div>
            <div class="col-md-12 justify-content-center">
                <table class="table table-hover dataTable">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        @can('remove-permission-from-user')
                            <th>Remover</th>
                        @endcan
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            @can('remove-permission-from-user')
                                <td>
                                    <button class="btn btn-danger btn-sm delete"
                                            data-route="users/permissions"
                                            data-gender="a"
                                            data-type="permissão"
                                            data-name="{{ $permission->name }}"
                                            data-id="{{ $user->id }}"
                                            value="{{ csrf_token() }}"
                                            data-permission="{{ $permission->id }}"
                                            id="{{ $permission->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            @endcan
                            <td>{{ $permission->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection