@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

<form action="{{ route('teacher-banji-subjects.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>教师</label>
        <select name="user_id" class="form-control">
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>学科</label>
        <select name="subject_id" class="form-control">
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>班级</label>
        <select name="banji_id" class="form-control">
            @foreach ($banjis as $banji)
                <option value="{{ $banji->id }}">{{ $banji->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">提交</button>
</form>
</div>
  </div>
</div>

@endsection
