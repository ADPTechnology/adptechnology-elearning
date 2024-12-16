<div class="form-group col-12">
    <label class="form-label">Selecciona uno o más usuarios: </label>
    <select name="users[]" class="form-control select2 select_users_coupons" multiple required>
        @foreach ($users as $user)
        <option value="{{ $user->id }}">
            {{ $user->email }} - {{ $user->full_name }}
        </option>
        @endforeach
    </select>

</div>
