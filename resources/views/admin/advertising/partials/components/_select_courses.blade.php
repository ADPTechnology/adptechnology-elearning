<div class="form-group col-12">

    <label class="form-label">Selecciona uno o más cursos: </label>
    <select name="courses[]" class="form-control select2 select_courses_coupons" multiple required>
        @foreach ($courses as $course)
        <option value="{{ $course->id }}">
            {{ $course->id }}: {{ $course->description }}
        </option>
        @endforeach
    </select>

</div>
