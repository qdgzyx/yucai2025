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
            {{-- 获取所有班级并按自然顺序排序 --}}
            @php
            $allBanji = App\Models\Banji::orderByRaw('CAST(SUBSTRING(name, LOCATE("年级", name) + 2, 1) AS UNSIGNED)')->get();
            @endphp

            @foreach($allBanji as $class)
            @php
            $submitted = $banjis->firstWhere('banji.id', $class->id);
            @endphp
            <tr style="background-color: {{ $submitted ? '#dff0d8' : '#fcf8e3' }}">
              <td>{{ $class->name }}</td>
              <td>{{ $submitted->total_expected ?? $class->student_count }}</td>
              <td>{{ $submitted->total_actual ?? 0 }}</td>
              <td>{{ $submitted->sick_leave_count ?? 0 }}</td>
              <td>
                @isset($submitted->sick_list)
                  @foreach(explode(',', $submitted->sick_list) as $list)
                    {{ $list }}<br>
                  @endforeach
                @else
                  -
                @endisset
              </td>
              <td>{{ $submitted->personal_leave_count ?? 0 }}</td>
              <td>
                @isset($submitted->personal_list)
                  @foreach(explode(',', $submitted->personal_list) as $list)
                    {{ $list }}<br>
                  @endforeach
                @else
                  -
                @endisset
              </td>
              <td>{{ $submitted->absent_count ?? ($class->student_count - ($submitted->total_actual ?? 0)) }}</td>
            </tr>
            @endforeach
          </tbody>

          <tfoot class="font-weight-bold">
            <tr>
              <td>合计</td>
              <td>{{ $allBanji->sum('student_count') }}</td>
              <td>{{ $banjis->sum('total_actual') }}</td>
              <td>{{ $banjis->sum('sick_leave_count') }}</td>
              <td>-</td>
              <td>{{ $banjis->sum('personal_leave_count') }}</td>
              <td>-</td>
              <td>{{ $allBanji->sum('student_count') - $banjis->sum('total_actual') }}</td>
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

<!-- 图表部分 -->
<div class="mt-4">
  <canvas id="attendanceChart" width="400" height="150"></canvas>
</div>

@section('scripts')
<!-- 下载 Chart.js 到 public/js 目录 -->
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
new Chart(document.getElementById('attendanceChart'), {
  type: 'bar',
  data: {
    labels: {!! $allBanji->pluck('name')->toJson() !!},
    datasets: [{
      label: '实到人数',
      data: {!! $allBanji->map(function($class) use ($banjis) {
        return $banjis->firstWhere('banji.id', $class->id)->total_actual ?? 0;
      })->toJson() !!},
      backgroundColor: {!! $allBanji->map(function($class) use ($banjis) {
        return $banjis->firstWhere('banji.id', $class->id) ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 206, 86, 0.6)';
      })->toJson() !!}
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
</script>
@endsection
@endsection