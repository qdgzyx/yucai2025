@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          Assignment
          <a class="btn btn-success float-xs-right" href="{{ route('assignments.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($assignments->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Subject_id</th> <th>Content</th> <th>Attachment</th> <th>User_id</th> <th>Publish_at</th> <th>Deadline</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($assignments as $assignment)
              <tr>
                <td class="text-xs-center"><strong>{{$assignment->id}}</strong></td>

                <td>{{$assignment->subject_id}}</td> <td>{{$assignment->content}}</td> <td>{{$assignment->attachment}}</td> <td>{{$assignment->user_id}}</td> <td>{{$assignment->publish_at}}</td> <td>{{$assignment->deadline}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('assignments.show', $assignment->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('assignments.edit', $assignment->id) }}">
                    E
                  </a>

                  <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $assignments->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
