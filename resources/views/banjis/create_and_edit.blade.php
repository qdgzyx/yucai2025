@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          Banji /
          @if($banji->id)
            Edit #{{ $banji->id }}
          @else
            Create
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
                    <label for="grade_id-field">Grade_id</label>
                    <input class="form-control" type="text" name="grade_id" id="grade_id-field" value="{{ old('grade_id', $banji->grade_id ) }}" />
                </div> 
                <div class="mb-3">
                	<label for="name-field">Name</label>
                	<input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $banji->name ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="student_count-field">Student_count</label>
                    <input class="form-control" type="text" name="student_count" id="student_count-field" value="{{ old('student_count', $banji->student_count ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="user_id-field">User_id</label>
                    <input class="form-control" type="text" name="user_id" id="user_id-field" value="{{ old('user_id', $banji->user_id ) }}" />
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('banjis.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
