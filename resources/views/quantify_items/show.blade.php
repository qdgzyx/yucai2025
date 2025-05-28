@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>QuantifyItem / Show #{{ $quantify_item->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('quantify_items.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('quantify_items.edit', $quantify_item->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Semester_id</label>
<p>
	{{ $quantify_item->semester_id }}
</p> <label>Name</label>
<p>
	{{ $quantify_item->name }}
</p> <label>Type</label>
<p>
	{{ $quantify_item->type }}
</p> <label>Score</label>
<p>
	{{ $quantify_item->score }}
</p> <label>Criteria</label>
<p>
	{{ $quantify_item->criteria }}
</p> <label>Status</label>
<p>
	{{ $quantify_item->status }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
