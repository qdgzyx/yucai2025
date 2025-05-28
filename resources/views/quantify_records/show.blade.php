@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>QuantifyRecord / Show #{{ $quantify_record->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('quantify_records.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('quantify_records.edit', $quantify_record->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Quantify_item_id</label>
<p>
	{{ $quantify_record->quantify_item_id }}
</p> <label>User_id</label>
<p>
	{{ $quantify_record->user_id }}
</p> <label>Score</label>
<p>
	{{ $quantify_record->score }}
</p> <label>Remark</label>
<p>
	{{ $quantify_record->remark }}
</p> <label>Assessed_at</label>
<p>
	{{ $quantify_record->assessed_at }}
</p> <label>Ip_address</label>
<p>
	{{ $quantify_record->ip_address }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
