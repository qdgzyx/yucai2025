@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          Semester
          <a class="btn btn-success float-xs-right" href="{{ route('semesters.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($semesters->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Academic_id</th> <th>Name</th> <th>Start_date</th> <th>End_date</th> <th>Is_current</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($semesters as $semester)
              <tr>
                <td class="text-xs-center"><strong>{{$semester->id}}</strong></td>

                <td>{{$semester->academic_id}}</td> <td>{{$semester->name}}</td> <td>{{$semester->start_date}}</td> <td>{{$semester->end_date}}</td> <td>{{$semester->is_current}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('semesters.show', $semester->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('semesters.edit', $semester->id) }}">
                    E
                  </a>

                  <form action="{{ route('semesters.destroy', $semester->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $semesters->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
