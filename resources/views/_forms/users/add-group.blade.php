<div class="form-group">
    <label for="users">Selecione o usuário</label>
    <select name="users"
            id="users"
            class="form-control">
        @if(isset($user))
            @foreach($users as $u)
                <option value="{{ $u->id }}"
                    @if(($u->id == $user->id) || ($u->id == old('users')))
                        selected
                    @endif
                >
                    {{ $u->name }}
                </option>
            @endforeach
        @else
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $u->id == old('users') ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        @endif
    </select>
    @if($errors->has('users'))
        <small class="text-danger">{{ $errors->first('users') }}</small>
    @endif
</div>
<div class="form-group">
    <label for="groups">Selecione os grupos:</label>
    <select name="groups"
            multiple="multiple"
            id="groups" class="form-control">
        @if(isset($user))
            @foreach($groups as $g)
                <option value="{{ $g->id }}"
                @if(app(config('acl.models.user'))->hasGroup(''))@endif
                @if(old('groups') != null)
                    {{ (in_array($g->id, old('groups')) ? 'selected' : '') }}
                        @endif
                >
                    {{ $permission->name }}
                </option>
            @endforeach
        @else
            @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                @if(old('groups') != null)
                    {{ in_array($permission->id, old('groups')) ? 'selected' : '' }}
                        @endif
                >
                    {{ $permission->name }}
                </option>
            @endforeach
        @endif
    </select>
</div>
