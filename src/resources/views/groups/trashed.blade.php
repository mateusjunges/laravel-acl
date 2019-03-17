@extends('acl::layouts.page')
@section('title', 'Grupos removidos')
@section('js')
    <script src="{{ asset('vendor/mateusjunges/acl/js/general/general.js') }}"></script>
    <script src="{{ asset('vendor/mateusjunges/acl/js/groups/trashed.js') }}"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">
                    Grupos removidos do sistema
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover dataTable"
                       id="deleted-users-list">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        @can('groups.restore')
                            <th>Restaurar</th>
                        @endcan
                        @can('groups.permanentlyDelete')
                            <th>Remover Permanentemente</th>
                        @endcan
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($groups as $group)
                        <tr>
                            <td>{{ $group->name }}</td>
                            @can('groups.restore')
                                <td>
                                    <button class="btn btn-success btn-sm restore"
                                            data-route="groups/restore"
                                            data-gender="o"
                                            data-type="grupo"
                                            data-name="{{ $group->name }}"
                                            data-id="{{ $group->id }}"
                                            value="{{ csrf_token() }}">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                </td>
                            @endcan
                            @can('groups.permanentlyDelete')
                                <td>
                                    <button class="btn btn-danger btn-sm permanently-delete"
                                            data-route="groups/delete"
                                            data-gender="o"
                                            data-type="grupo"
                                            data-name="{{ $group->name }}"
                                            data-id="{{ $group->id }}"
                                            value="{{ csrf_token() }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            @endcan
                            <td>{{ $group->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection