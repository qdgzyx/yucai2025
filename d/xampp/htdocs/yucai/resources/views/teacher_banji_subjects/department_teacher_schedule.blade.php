@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">级部教师安排表</h5>
                </div>

                <div class="card-body">
                    <!-- 学期选择 -->
                    <form action="{{ route('teacher-banji-subjects.department-schedule') }}" method="GET" class="form-inline mb-3">
                        <div class="form-group mr-3">
                            <label for="semester_filter" class="mr-2">选择学期：</label>
                            <select name="semester_filter" id="semester_filter" class="form-control" onchange="this.form.submit()">
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ request('semester_filter') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-3">
                            <label for="department_filter" class="mr-2">选择级部：</label>
                            <select name="department_filter" id="department_filter" class="form-control" onchange="this.form.submit()">
                                <option value="">全部级部</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_filter') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <!-- 教师安排表 -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                @foreach ($subjects as $subject)
                                    <th>{{ $subject->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banjis as $banji)
                                <tr>
                                    <td>{{ $banji->name }}</td>
                                    @foreach ($subjects as $subject)
                                        <td>
                                            @php
                                                $teacher = $schedules->where('banji_id', $banji->id)
                                                    ->where('subject_id', $subject->id)
                                                    ->first();
                                            @endphp
                                            {{ $teacher ? $teacher->user->name : '' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Excel 导出按钮 -->
                    <a href="{{ route('teacher-banji-subjects.export', ['semester' => request('semester_filter'), 'department' => request('department_filter')]) }}" class="btn btn-primary">
                        <i class="fas fa-file-excel"></i> 导出为 Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection