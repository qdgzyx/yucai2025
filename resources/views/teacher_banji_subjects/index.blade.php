@extends('layouts.app')

@section('content')
    <!-- 新增班级筛选表单 -->
    <div class="mb-4">
        <form action="{{ route('teacher-banji-subjects.index') }}" method="GET" class="form-inline">
            <div class="form-group mr-3">
                <label for="banji_filter" class="mr-2">选择班级：</label>
                <select name="banji_filter" id="banji_filter" class="form-control" onchange="this.form.submit()">
                    <option value="">全部班级</option>
                    @foreach ($banjis as $banjiOption)
                        <option value="{{ $banjiOption->id }}" {{ request('banji_filter') == $banjiOption->id ? 'selected' : '' }}>
                            {{ $banjiOption->grade->name }} - {{ $banjiOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
               
                <th>班级</th>
                <th>学科</th> 
                <th>教师</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($relations as $relation)
                @if (!request('banji_filter') || request('banji_filter') == $relation->banji_id)
                <tr>
                   
                    <td>{{ $relation->banji->name }}</td>
                    <td>{{ $relation->subject->name }}</td> 
                    <td>{{ $relation->user->name }}</td>
                    
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    {{ $relations->appends(['banji_filter' => request('banji_filter')])->links() }}
@endsection