@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          出勤报告表
          <a class="btn btn-success float-xs-right" href="{{ route('reports.create') }}">新报</a>
        </h1>
      </div>

      <div class="card-body">
        @if($reports->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">序号</th>
               <th>日期</th>
    <th>班级</th>
    <th>应到人数</th>
    <th>实到人数</th>
    <th>病假人数</th>
    <th>病假名单</th>
    <th>事假人数</th>
    <th>事假名单</th>
    <th>缺勤人数</th>
    
    <th>状态</th>
    <th class="text-xs-right">操作</th>
              </tr>
            </thead>

            <tbody>
              @foreach($reports as $report)
              <tr>
                <td class="text-xs-center"><strong>{{$report->id}}</strong></td>

                <td>{{$report->date}}</td> <td>{{$report->banji_id}}</td> 
                <td>{{$report->total_expected}}</td> <td>{{$report->total_actual}}</td>
                 <td>{{$report->sick_leave_count}}</td> <td>{{$report->sick_list}}</td> 
                 <td>{{$report->personal_leave_count}}</td> <td>{{$report->personal_list}}</td> 
                 <td>{{$report->absent_count}}</td>  <td>{{$report->report_status}}</td>

                <td class="text-xs-right">
                @can('update', $report)
            <div class="operate">
               <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-outline-secondary btn-sm" role="button">
                <i class="far fa-edit"></i> 编辑
              </a>
              <form action="{{ route('reports.destroy', $report->id) }}" method="post"
                    style="display: inline-block;"
                    onsubmit="return confirm('您确定要删除吗？');">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-outline-warning btn-sm">
                  <i class="far fa-trash-alt"></i> 删除
                </button>
              </form>
            </div>
          @endcan  


                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $reports->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">无数据！</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
