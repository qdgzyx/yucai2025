@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          班级管理
          <a class="btn btn-success float-xs-right" href="{{ route('banjis.create') }}">新建</a>
        </h1>
      </div>

      <div class="card-body">
        @if($banjis->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>年级名称</th>
                <th>班级名称</th>
                <th>学生人数</th>
                <th>班主任</th>
                <th class="text-xs-right">操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach($banjis as $banji)
              <tr>
                <td class="text-xs-center"><strong>{{ $banji->id }}</strong></td>
                <td>{{ $banji->grade->name ?? '未分配年级' }}</td>
                <td>{{ $banji->name }}</td>
                <td>{{ $banji->student_count }}</td>
                <td>{{ $banji->user->name ?? '未分配班主任' }}</td>
                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('banjis.show', $banji->id) }}">V</a>
                  <a class="btn btn-sm btn-warning" href="{{ route('banjis.edit', $banji->id) }}">E</a>
                  <form action="{{ route('banjis.destroy', $banji->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-danger">D</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $banjis->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">无班级!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
