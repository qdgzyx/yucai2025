@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h3 class="mb-0">
          年级出勤汇总表
          <small class="float-right">{{ $today }}</small>
        </h3>
      </div>

      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="thead-light">
            <tr>
              <th>班级名称</th>
              <th>应到人数</th>
              <th>实到人数</th>
              <th>病假人数</th>
              <th>病假名单</th>
              <th>事假人数</th>
              <th>事假名单</th>
              <th>缺勤人数</th>
            </tr>
          </thead>
          
          <tbody>
            @foreach($banjis as $banji)
            <tr>
              <td>{{ $banji->banji->name ?? '未命名班级' }}</td>
              <td>{{ $banji->total_expected }}</td>
              <td>{{ $banji->total_actual }}</td>
              <td>{{ $banji->sick_leave_count }}</td>
             
              <td>
                @foreach(explode(',', $banji->sick_list) as $list)
                  {{ $list }}<br>
                @endforeach
              </td>
              <td>{{ $banji->personal_leave_count }}</td>
              <td>
                @foreach(explode(',', $banji->personal_list) as $list)
                  {{ $list }}<br>
                @endforeach
              </td>
              <td>{{ $banji->absent_count }}</td>
            </tr>
            @endforeach
          </tbody>

          <tfoot class="font-weight-bold">
            <tr>
              <td>合计</td>
              <td>{{ $totals['total_expected'] }}</td>
              <td>{{ $totals['total_actual'] }}</td>
              <td>{{ $totals['sick_leave_count'] }}</td>
              <td>-</td>
              <td>{{ $totals['personal_leave_count'] }}</td>
              <td>-</td>
              <td>{{ $totals['absent_count'] }}</td>
            </tr>
          </tfoot>
        </table>

        <div class="mt-3">
          <a href="{{ route('reports.index') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> 导出Excel
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- 在表格下方添加图表 -->
<div class="mt-4">
  <canvas id="attendanceChart" width="400" height="150"></canvas>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('attendanceChart'), {
  type: 'bar',
  data: {
    labels: {!! $banjis->pluck('banji.name')->toJson() !!},
    datasets: [{
      label: '实到人数',
      data: {!! $banjis->pluck('total_actual')->toJson() !!}
    }]
  }
});
</script>
@endsection


@endsection