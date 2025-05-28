@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>Semester / Show #{{ $semester->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('semesters.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('semesters.edit', $semester->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Academic_id</label>
<p>
	{{ $semester->academic_id }}
</p> <label>Name</label>
<p>
	{{ $semester->name }}
</p> <label>Start_date</label>
<p>
	{{ $semester->start_date }}
</p> <label>End_date</label>
<p>
	{{ $semester->end_date }}
</p> <label>Is_current</label>
<p>
	{{ $semester->is_current }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
