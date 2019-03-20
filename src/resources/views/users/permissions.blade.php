@extends('acl::layouts.page')
@section('title', 'Permissões do usuário '.$user->name)
@section('js')

@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Lista de permisões</h2>
            </div>
            <div class="col-md-12 justify-content-center">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Remover</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete"
                                        data-route="users/permissions"
                                        data-gender="a"
                                        data-id="{{ $permission->relation_id }}"
                                        id="{{ $permission->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <td>{{ $permission->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection