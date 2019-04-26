@csrf
<div class="form-group">
    <label for="user">Selecione o usuário:</label>
    <select name="user"
            id="user"
            class="form-control">
        @if(isset($user))
            @foreach($users as $u)
                <option value="{{ $user->id }}"
                        @if(($u->id == $user->id) || ($u->id == old('user')))
                            selected
                        @endif
                >
                    {{ $u->name }}</option>
            @endforeach
        @else
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $u->id == old('user') ? 'selected' : '' }}>
                    {{ $u->name }}</option>
            @endforeach
        @endif
    </select>
    @if($errors->has('user'))
        <small class="text-danger">{{ $errors->first('user') }}</small>
    @endif
</div>
<div class="form-group">
    <label for="user-permissions">Permissões:</label>
    <select name="permissions[]"
            style="width: 100%"
            multiple
            id="user-permissions"
            class="form-control">
        @if(isset($user))
            @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                        {{ ($user->hasDirectPermission($permission->slug) ? 'selected' : '') }}
                @if(old('$permissions') != null)
                    {{ (in_array($permission->id, old('permissions')) ? 'selected' : '') }}
                        @endif
                >
                    {{ $permission->name }}
                </option>
            @endforeach
        @else
            @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                @if(old('permissions') != null)
                    {{ in_array($permission->id, old('permissions')) ? 'selected' : '' }}
                        @endif
                >
                    {{ $permission->name }}
                </option>
            @endforeach
        @endif
    </select>
    @if($errors->has('permissions'))
        <span class="text-danger">{{ $errors->first('permissions') }}</span>
@endif