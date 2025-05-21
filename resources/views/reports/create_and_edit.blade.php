@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          Report /
          @if($report->id)
            Edit #{{ $report->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($report->id)
          <form action="{{ route('reports.update', $report->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('reports.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                    <label for="date-field">Date</label>
                    <input class="form-control" type="text" name="date" id="date-field" value="{{ old('date', $report->date ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="banji_id-field">Banji_id</label>
                    <input class="form-control" type="text" name="banji_id" id="banji_id-field" value="{{ old('banji_id', $report->banji_id ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="total_expected-field">Total_expected</label>
                    <input class="form-control" type="text" name="total_expected" id="total_expected-field" value="{{ old('total_expected', $report->total_expected ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="total_actual-field">Total_actual</label>
                    <input class="form-control" type="text" name="total_actual" id="total_actual-field" value="{{ old('total_actual', $report->total_actual ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="sick_leave_count-field">Sick_leave_count</label>
                    <input class="form-control" type="text" name="sick_leave_count" id="sick_leave_count-field" value="{{ old('sick_leave_count', $report->sick_leave_count ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="sick_list-field">Sick_list</label>
                    <input class="form-control" type="text" name="sick_list" id="sick_list-field" value="{{ old('sick_list', $report->sick_list ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="personal_leave_count-field">Personal_leave_count</label>
                    <input class="form-control" type="text" name="personal_leave_count" id="personal_leave_count-field" value="{{ old('personal_leave_count', $report->personal_leave_count ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="personal_list-field">Personal_list</label>
                    <input class="form-control" type="text" name="personal_list" id="personal_list-field" value="{{ old('personal_list', $report->personal_list ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="absent_count-field">Absent_count</label>
                    <input class="form-control" type="text" name="absent_count" id="absent_count-field" value="{{ old('absent_count', $report->absent_count ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="absent_list-field">Absent_list</label>
                    <input class="form-control" type="text" name="absent_list" id="absent_list-field" value="{{ old('absent_list', $report->absent_list ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="report_status-field">Report_status</label>
                    <input class="form-control" type="text" name="report_status" id="report_status-field" value="{{ old('report_status', $report->report_status ) }}" />
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('reports.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
