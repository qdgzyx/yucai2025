@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          Report
          <a class="btn btn-success float-xs-right" href="{{ route('reports.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($reports->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Date</th> <th>Banji_id</th> <th>Total_expected</th> <th>Total_actual</th> <th>Sick_leave_count</th> <th>Sick_list</th> <th>Personal_leave_count</th> <th>Personal_list</th> <th>Absent_count</th> <th>Absent_list</th> <th>Report_status</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($reports as $report)
              <tr>
                <td class="text-xs-center"><strong>{{$report->id}}</strong></td>

                <td>{{$report->date}}</td> <td>{{$report->banji_id}}</td> <td>{{$report->total_expected}}</td> <td>{{$report->total_actual}}</td> <td>{{$report->sick_leave_count}}</td> <td>{{$report->sick_list}}</td> <td>{{$report->personal_leave_count}}</td> <td>{{$report->personal_list}}</td> <td>{{$report->absent_count}}</td> <td>{{$report->absent_list}}</td> <td>{{$report->report_status}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('reports.show', $report->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('reports.edit', $report->id) }}">
                    E
                  </a>

                  <form action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $reports->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
