@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>Report / Show #{{ $report->id }}</h1>
      </div>

      <div class="card-body">
        <div class="card-block bg-light">
          <div class="row">
            <div class="col-md-6">
              <a class="btn btn-link" href="{{ route('reports.index') }}"><- Back</a>
            </div>
            <div class="col-md-6">
              <a class="btn btn-sm btn-warning float-right mt-1" href="{{ route('reports.edit', $report->id) }}">
                Edit
              </a>
            </div>
          </div>
        </div>
        <br>

        <label>Date</label>
<p>
	{{ $report->date }}
</p> <label>Banji_id</label>
<p>
	{{ $report->banji_id }}
</p> <label>Total_expected</label>
<p>
	{{ $report->total_expected }}
</p> <label>Total_actual</label>
<p>
	{{ $report->total_actual }}
</p> <label>Sick_leave_count</label>
<p>
	{{ $report->sick_leave_count }}
</p> <label>Sick_list</label>
<p>
	{{ $report->sick_list }}
</p> <label>Personal_leave_count</label>
<p>
	{{ $report->personal_leave_count }}
</p> <label>Personal_list</label>
<p>
	{{ $report->personal_list }}
</p> <label>Absent_count</label>
<p>
	{{ $report->absent_count }}
</p> <label>Absent_list</label>
<p>
	{{ $report->absent_list }}
</p> <label>Report_status</label>
<p>
	{{ $report->report_status }}
</p>
      </div>
    </div>
  </div>
</div>

@endsection
