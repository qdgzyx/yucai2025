@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
         作业审核
          <a class="btn btn-success float-xs-right" href="{{ route('assignments.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($assignments->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>学科</th> <th>内容</th> <th>附件</th> <th>布置老师</th> <th>布置时间</th> <th>截止时间</th>
                <th>审核状态</th>
                <th>审核操作</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($assignments as $assignment)
              <tr>
                <td class="text-xs-center"><strong>{{$assignment->id}}</strong></td>

                <td>{{$assignment->subject->name ?? ''}}</td>
                <td>{{ Str::limit($assignment->content, 20) }}</td>
                <td>
                  @if($assignment->attachment)
                  <a href="{{ asset('storage/'.$assignment->attachment) }}" download>下载</a>
                  @endif
                </td>
                <td>{{$assignment->teacher->name ?? ''}}</td>
                <td>{{$assignment->publish_at}}</td>
                <td>{{$assignment->deadline}}</td>
                <td>
                  @if($assignment->status === 'pending')
                    <span class="badge badge-warning">待审核</span>
                  @elseif($assignment->status === 'approved')
                    <span class="badge badge-success">已通过</span>
                  @else
                    <span class="badge badge-danger">已驳回</span>
                  @endif
                </td>
                <td>
                  @can('group_approve', $assignment)
                    <form action="{{ route('assignments.approve', $assignment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success" name="type" value="group">组长通过</button>
                    </form>
                    <form action="{{ route('assignments.reject', $assignment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger" name="type" value="group">组长驳回</button>
                    </form>
                  @endcan

                  @can('director_approve', $assignment)
                    <form action="{{ route('assignments.approve', $assignment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success" name="type" value="director">主任通过</button>
                    </form>
                    <form action="{{ route('assignments.reject', $assignment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger" name="type" value="director">主任驳回</button>
                    </form>
                  @endcan
                </td>

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
