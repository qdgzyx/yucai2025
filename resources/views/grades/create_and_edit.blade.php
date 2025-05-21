@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          Grade /
          @if($grade->id)
            Edit #{{ $grade->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($grade->id)
          <form action="{{ route('grades.update', $grade->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('grades.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                	<label for="name-field">Name</label>
                	<input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $grade->name ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="year-field">Year</label>
                    <input class="form-control" type="text" name="year" id="year-field" value="{{ old('year', $grade->year ) }}" />
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('grades.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
