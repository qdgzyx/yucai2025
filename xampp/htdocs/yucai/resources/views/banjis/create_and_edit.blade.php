
<div class="mb-3">
    <label for="user_id-field">班主任</label>
    <select class="form-control" name="user_id" id="user_id-field">
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{ old('user_id', $banji->user_id) == $teacher->id ? 'selected' : '' }}>
                {{ $teacher->name }}
            </option>
        @endforeach
    </select>
</div>
