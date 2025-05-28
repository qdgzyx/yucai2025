@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>Academic / Show #{{ $academic->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('academics.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('academics.edit', $academic->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Name</label>
<p>
	{{ $academic->name }}
</p> <label>Start_date</label>
<p>
	{{ $academic->start_date }}
</p> <label>End_date</label>
<p>
	{{ $academic->end_date }}
</p> <label>Is_current</label>
<p>
	{{ $academic->is_current }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
