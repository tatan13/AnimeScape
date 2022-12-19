@for ($i = 2023; $i >= 1960; $i--)
    <option value="{{ $i }}" {{ is_null($year ?? null) ? '' : ($year == $i ? 'selected' : '') }}>
        {{ $i }}</option>
@endfor
