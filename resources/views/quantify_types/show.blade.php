@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>QuantifyType / Show #{{ $quantify_type->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('quantify_types.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('quantify_types.edit', $quantify_type->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Parent_id</label>
<p>
	{{ $quantify_type->parent_id }}
</p> <label>Code</label>
<p>
	{{ $quantify_type->code }}
</p> <label>Name</label>
<p>
	{{ $quantify_type->name }}
</p> <label>Weight</label>
<p>
	{{ $quantify_type->weight }}
</p> <label>Order</label>
<p>
	{{ $quantify_type->order }}
</p> <label>Requirements</label>
<p>
	{{ $quantify_type->requirements }}
</p> <label>User_id</label>
<p>
	{{ $quantify_type->user_id }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
