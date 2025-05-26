@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          Subject
          <a class="btn btn-success float-xs-right" href="{{ route('subjects.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($subjects->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Name</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($subjects as $subject)
              <tr>
                <td class="text-xs-center"><strong>{{$subject->id}}</strong></td>

                <td>{{$subject->name}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('subjects.show', $subject->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('subjects.edit', $subject->id) }}">
                    E
                  </a>

                  <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $subjects->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
