@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>Assignment / Show #{{ $assignment->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('assignments.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('assignments.edit', $assignment->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Subject_id</label>
<p>
	{{ $assignment->subject_id }}
</p> <label>Content</label>
<p>
	{{ $assignment->content }}
</p> <label>Attachment</label>
<p>
	{{ $assignment->attachment }}
</p> <label>User_id</label>
<p>
	{{ $assignment->user_id }}
</p> <label>Publish_at</label>
<p>
	{{ $assignment->publish_at }}
</p> <label>Deadline</label>
<p>
	{{ $assignment->deadline }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
