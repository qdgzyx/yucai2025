@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          Academic
          <a class="btn btn-success float-xs-right" href="{{ route('academics.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($academics->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Name</th> <th>Start_date</th> <th>End_date</th> <th>Is_current</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($academics as $academic)
              <tr>
                <td class="text-xs-center"><strong>{{$academic->id}}</strong></td>

                <td>{{$academic->name}}</td> <td>{{$academic->start_date}}</td> <td>{{$academic->end_date}}</td> <td>{{$academic->is_current}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('academics.show', $academic->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('academics.edit', $academic->id) }}">
                    E
                  </a>

                  <form action="{{ route('academics.destroy', $academic->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $academics->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
