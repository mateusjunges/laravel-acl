<input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
<div class="form-group">
    <label for="group-name">Nome:</label>
    <input type="text"
           id="group-name"
           minlength="3"
           name="name"
           placeholder="Informe o nome do novo grupo"
           value="{{ isset($group) ? $group->name : old('name') }}"
           class="form-control">
    @if($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="group-slug">Slug do grupo:</label>
    <input type="text"
           id="group-slug"
           minlength="3"
           name="slug"
           placeholder="Informe o nome do novo grupo"
           value="{{ isset($group) ? $group->slug : old('slug') }}"
           class="form-control">
    @if($errors->has('slug'))
        <span class="text-danger">{{ $errors->first('slug') }}</span>
    @endif
</div>
<label for="group-description">Descrição:</label>
<div class="form-group">
    <textarea name="description"
              id="group-description"
              placeholder="Informe a descrição deste grupo"
              minlength="5"
              class="form-control"
              cols="30" rows="10">{{ isset($group) ? $group->description : old('description') }}</textarea>
    @if($errors->has('description'))
        <span class="text-danger">{{ $errors->first('description') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="group-permissions">Permissões:</label>
    <select name="permissions[]"
            style="width: 100%"
            multiple
            id="group-permissions"
            class="form-control">
        @if(isset($group))
            @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                        {{ ($group->hasPermission($permission->id) ? 'selected' : '') }}
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
</div>