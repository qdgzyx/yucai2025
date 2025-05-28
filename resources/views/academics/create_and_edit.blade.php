@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          Academic /
          @if($academic->id)
            Edit #{{ $academic->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($academic->id)
          <form action="{{ route('academics.update', $academic->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('academics.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                	<label for="name-field">Name</label>
                	<input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $academic->name ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="start_date-field">Start_date</label>
                    <input class="form-control" type="text" name="start_date" id="start_date-field" value="{{ old('start_date', $academic->start_date ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="end_date-field">End_date</label>
                    <input class="form-control" type="text" name="end_date" id="end_date-field" value="{{ old('end_date', $academic->end_date ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="is_current-field">Is_current</label>
                    <input class="form-control" type="text" name="is_current" id="is_current-field" value="{{ old('is_current', $academic->is_current ) }}" />
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('academics.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
