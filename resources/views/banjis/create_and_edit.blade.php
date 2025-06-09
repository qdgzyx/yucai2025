@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          班级 /
          @if($banji->id)
            编辑 #{{ $banji->id }}
          @else
           新建
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($banji->id)
          <form action="{{ route('banjis.update', $banji->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('banjis.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                    <label for="grade_id-field">年级名称</label>
                    <select class="form-control" name="grade_id" id="grade_id-field">
                        @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}" {{ old('grade_id', $banji->grade_id) == $grade->id ? 'selected' : '' }}>
                                {{ $grade->name }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div class="mb-3">
                	<label for="name-field">班级名称</label>
                	<input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $banji->name ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="student_count-field">学生人数</label>
                    <input class="form-control" type="text" name="student_count" id="student_count-field" value="{{ old('student_count', $banji->student_count ) }}" />
                </div> 
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

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">保存</button>
            <a class="btn btn-link float-xs-right" href="{{ route('banjis.index') }}"> <- 返回列表</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection