@extends('acl::layouts.page')
@section('title', 'Grupos do sistema')
@section('js')
    <script src="{{ asset('vendor/mateusjunges/acl/js/general/general.js') }}"></script>
    <script src="{{ asset('vendor/mateusjunges/acl/js/groups/groups.js') }}"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Lista de grupos do sistema</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table-hover table dataTable">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        {{--@can('groups.viewPermissions')--}}
                            <th>Ver Permissões</th>
                        {{--@endcan--}}
                        {{--@can('groups.update')--}}
                            <th>Editar</th>
                        {{--@endcan--}}
                        {{--@can('groups.delete')--}}
                            <th>Remover</th>
                        {{--@endcan--}}
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($groups as $group)
                        <tr>
                            <td>{{ $group->name }}</td>
                            {{--@can('groups.viewPermissions')--}}
                                <td>
                                    <a href="{{ route('groups.show', $group->id) }}">
                                        <button class="btn btn-warning btn-sm permissions">
                                            <i class="fa fa-tags"></i>
                                        </button>
                                    </a>
                                </td>
                            {{--@endcan--}}
                            {{--@can('groups.update')--}}
                                <td>
                                    <a href="{{ route('groups.edit', $group->id) }}">
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </a>
                                </td>
                            {{--@endcan--}}
                            {{--@can('groups.delete')--}}
                                <td>
                                    <button class="btn btn-danger btn-sm delete"
                                            value="{{ csrf_token() }}"
                                            data-route="groups"
                                            data-gender="o"
                                            data-type="grupo"
                                            data-name="{{ $group->name }}"
                                            data-id="{{ $group->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            {{--@endcan--}}
                            <td>{{ $group->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection