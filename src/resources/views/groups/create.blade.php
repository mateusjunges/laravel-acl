@extends('acl::layouts.page')
@section('title', 'Novo grupo')
@section('js')
    <script src="{{ asset('vendor/mateusjunges/acl/js/groups/create-edit.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col col-md-12">
                <h1 class="text-center">
                    Criar um novo grupo de permissões
                </h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-push-3 col-md-pull-3">
                <form action="{{ route('groups.store') }}" method="post">
                    @include('acl::_forms.groups.group')
                    <button class="btn btn-outline-success btn-block" type="submit">
                        Cadastrar grupo
                    </button>
                </form>
            </div>
        </div>
    </div>
@stop