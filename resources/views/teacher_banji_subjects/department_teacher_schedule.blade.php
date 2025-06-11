@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">教师安排表</h5>
                </div>

                <div class="card-body">
                    <!-- 学期和级部选择调整为一行 -->
                    <form action="{{ route('teacher-banji-subjects.department-schedule') }}" method="GET" class="mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="semester_filter">选择学期：</label>
                                    <select name="semester_filter" id="semester_filter" class="form-control" onchange="this.form.submit()">
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}" {{ request('semester_filter') == $semester->id ? 'selected' : '' }}>
                                                {{ $semester->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_filter">选择级部：</label>
                                    <select name="grade_filter" id="grade_filter" class="form-control" onchange="this.form.submit()">
                                        <option value="">全部级部</option>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ request('grade_filter') == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- 居中显示教师安排表 -->
                    <div class="d-flex justify-content-center">
                        <table class="table table-bordered" style="width: auto;">
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
                    </div>

                    <!-- Excel 导出按钮 -->
                    <a href="{{ route('teacher-banji-subjects.export', ['semester' => request('semester_filter'), 'grade' => request('grade_filter')]) }}" class="btn btn-primary">
                        <i class="fas fa-file-excel"></i> 导出为 Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection