<label class="form-label">Plan: </label>
<select name="plan" class="form-control select2 select_plan_course" required>
    <option value="" disabled selected>Selecciona un plan</option>
    @foreach ($plans as $plan)
        <option value="{{ $plan->id }}">
            {{ $plan->title }} : ${{ $plan->price }} x{{ getDetailPlan($plan) }}
        </option>
    @endforeach
</select>
