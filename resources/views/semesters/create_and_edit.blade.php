@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          Semester /
          @if($semester->id)
            Edit #{{ $semester->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($semester->id)
          <form action="{{ route('semesters.update', $semester->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('semesters.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                    <label for="academic_id-field">学年</label>
                    <select class="form-control" name="academic_id" id="academic_id-field">
                        @foreach($academics as $academic)
                            <option value="{{ $academic->id }}" {{ old('academic_id', $semester->academic_id) == $academic->id ? 'selected' : '' }}>
                                {{ $academic->name }}
                            </option>
                        @endforeach
                    </select>
                </div> 

                <div class="mb-3">
                    <label for="name-field">学期名称</label>
                    <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $semester->name ) }}" />
                </div> 

                <div class="mb-3">
                    <label for="start_date-field">开始日期</label>
                    <input class="form-control" type="date" name="start_date" id="start_date-field" value="{{ old('start_date', $semester->start_date ? $semester->start_date->format('Y-m-d') : '') }}" />
                </div> 

                <div class="mb-3">
                    <label for="end_date-field">结束日期</label>
                    <input class="form-control" type="date" name="end_date" id="end_date-field" value="{{ old('end_date', $semester->end_date ? $semester->end_date->format('Y-m-d') : '') }}" />
                </div> 

                <div class="mb-3">
                    <label for="is_current-field">是否当前学期</label>
                    <select class="form-control" name="is_current" id="is_current-field">
                        <option value="0" {{ old('is_current', $semester->is_current) == 0 ? 'selected' : '' }}>否</option>
                        <option value="1" {{ old('is_current', $semester->is_current) == 1 ? 'selected' : '' }}>是</option>
                    </select>
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('semesters.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
