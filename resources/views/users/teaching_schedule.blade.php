@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card">
      <div class="card-header">
        <h4><i class="glyphicon glyphicon-list-alt"></i> 班级任课教师安排表</h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="thead-light">
            <tr>
              <th>班级</th>
              <th>班主任</th>
              @foreach($subjects as $subject)
                <th>{{ $subject->name }}教师</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($banjis as $banji)
            <tr>
              <td>{{ $banji->name }}</td>
              <td>{{ $banji->headTeacher->name ?? '暂无' }}</td>
              @foreach($subjects as $subject)
                <td>
                  @php
                    $teacher = $banji->teacherBanjiSubjects
                        ->where('subject_id', $subject->id)
                        ->first()->teacher ?? null;
                  @endphp
                  {{ $teacher->name ?? '未安排' }}
                </td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection