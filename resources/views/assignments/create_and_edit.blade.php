@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          Assignment /
          @if($assignment->id)
            Edit #{{ $assignment->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($assignment->id)
          <form action="{{ route('assignments.update', $assignment->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('assignments.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                    <label for="subject_id-field">Subject_id</label>
                    <input class="form-control" type="text" name="subject_id" id="subject_id-field" value="{{ old('subject_id', $assignment->subject_id ) }}" />
                </div> 
                <div class="mb-3">
                	<label for="content-field">Content</label>
                	<textarea name="content" id="content-field" class="form-control" rows="3">{{ old('content', $assignment->content ) }}</textarea>
                </div> 
                <div class="mb-3">
                	<label for="attachment-field">Attachment</label>
                	<input class="form-control" type="text" name="attachment" id="attachment-field" value="{{ old('attachment', $assignment->attachment ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="user_id-field">User_id</label>
                    <input class="form-control" type="text" name="user_id" id="user_id-field" value="{{ old('user_id', $assignment->user_id ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="publish_at-field">Publish_at</label>
                    <input class="form-control" type="text" name="publish_at" id="publish_at-field" value="{{ old('publish_at', $assignment->publish_at ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="deadline-field">Deadline</label>
                    <input class="form-control" type="text" name="deadline" id="deadline-field" value="{{ old('deadline', $assignment->deadline ) }}" />
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('assignments.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
