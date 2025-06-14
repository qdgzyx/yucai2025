@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>Banji / Show #{{ $banji->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('banjis.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('banjis.edit', $banji->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Grade_id</label>
<p>
	{{ $banji->grade_id }}
</p> <label>Name</label>
<p>
	{{ $banji->name }}
</p> <label>Student_count</label>
<p>
	{{ $banji->student_count }}
</p> <label>User_id</label>
<p>
	{{ $banji->user_id }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
