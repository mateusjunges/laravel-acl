@extends('acl::layouts.page')
@section('title', 'Atribuir permissão ao usuário')
@section('js')
    <script src="{{ asset('vendor/mateusjunges/acl/js/users/permissions.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col col-md-12">
                <h1 class="text-center">
                    Atribuir permissões a usuário
                </h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-push-3 col-md-pull-3">
                <form action="{{ route('user.permissions.store') }}" method="post">
                    @include('acl::_forms.users.add-permission')
                    <div class="form-group">
                        <button class="btn btn-outline-success btn-block" type="submit">
                            Atribuir permissões
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop