<div class="form-group">
    <label for="users">Selecione o usuário</label>
    <select name="user"
            id="users"
            class="form-control">
        @if(isset($user))
            @foreach($users as $u)
                <option value="{{ $u->id }}"
                    @if(($u->id == $user->id) || ($u->id == old('user')))
                        selected
                    @endif
                >
                    {{ $u->name }}
                </option>
            @endforeach
        @else
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $u->id == old('user') ? 'selected' : '' }}>
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
    <select name="groups[]"
            multiple="multiple"
            id="groups" class="form-control">
        @if(isset($user))
            @foreach($groups as $g)
                <option value="{{ $g->id }}"
                        {{ $user->hasGroup($g) ? 'selected' : '' }}
                @if(old('groups') != null)
                    {{ (in_array($g->id, old('groups')) ? 'selected' : '') }}
                        @endif
                >
                    {{ $g->name }}
                </option>
            @endforeach
        @else
            @foreach($groups as $g)
                <option value="{{ $g->id }}"
                @if(old('groups') != null)
                    {{ in_array($g->id, old('groups')) ? 'selected' : '' }}
                        @endif
                >
                    {{ $g->name }}
                </option>
            @endforeach
        @endif
    </select>
</div>
