@extends('acl::layouts.page')
@section('title', 'Permissões do grupo'.$group->name)
@section('js')
    <script src="{{ asset('vendor/mateusjunges/acl/js/general/general.js') }}"></script>
    <script src="{{ asset('vendor/mateusjunges/acl/js/groups/permissions.js') }}"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">
                    Lista de permissões do grupo {{ $group->name }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="group-permissions-list" class="table table-hover dataTable">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        @can('remove-group')
                            <th>Remover</th>
                        @endcan
                        <th>Slug</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            @can('remove-group')
                                <td>
                                    <button class="btn btn-danger btn-sm delete"
                                            data-route="groups/permissions"
                                            data-gender="a"
                                            data-type="permissão"
                                            data-name="{{ $permission->name }}"
                                            data-id="{{ $group->id }}"
                                            data-permission="{{ $permission->id }}"
                                            value="{{ csrf_token() }}"
                                            id="delete-group-permission">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            @endcan
                            <td>{{ $permission->slug }}</td>
                            <td>{{ $permission->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection