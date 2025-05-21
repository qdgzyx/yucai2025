@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          Banji
          <a class="btn btn-success float-xs-right" href="{{ route('banjis.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($banjis->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Grade_id</th> <th>Name</th> <th>Student_count</th> <th>User_id</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($banjis as $banji)
              <tr>
                <td class="text-xs-center"><strong>{{$banji->id}}</strong></td>

                <td>{{$banji->grade_id}}</td> <td>{{$banji->name}}</td> <td>{{$banji->student_count}}</td> <td>{{$banji->user_id}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('banjis.show', $banji->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('banjis.edit', $banji->id) }}">
                    E
                  </a>

                  <form action="{{ route('banjis.destroy', $banji->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $banjis->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
