<form action="{{ route('teacher-banji-subjects.store') }}" method="POST">
    @csrf
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>班级</th>
                <th>班主任</th>
                @foreach ($subjects as $subject)
                    <th>{{ $subject->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($banjis as $banji)
            <tr>
                <td>{{ $banji->name }}</td>
                <td>{{ $banji->head_teacher->name ?? '' }}</td>
                @foreach ($subjects as $subject)
                <td>
                    <input type="checkbox" 
                           name="banji_subjects[{{ $banji->id }}][]" 
                           value="{{ $subject->id }}">
                    <select name="teacher_selection[{{ $banji->id }}][{{ $subject->id }}]" class="form-control">
                        <option value="">未选择</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">提交</button>
</form>