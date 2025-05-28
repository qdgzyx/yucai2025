@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
  <div class="d-flex align-items-center justify-content-between">
   
    <div class="flex-grow-1 text-center">
      <h3 class="m-0 position-relative" style="left: 1.2rem"> {{-- 视觉平衡微调 --}}
        {{ $currentGrade }}出勤汇总表
      </h3>
    </div>
    
    
    <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center">
         @csrf
    <div class="input-group date-picker-group">
            <input type="date" 
                   name="date" 
                   class="form-control"
                   value="{{ $selectedDate ?? $today }}"
                   max="{{ now()->toDateString() }}"
                   style="width: 160px">
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
  </div>
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
            @foreach($allBanji as $class) <!-- 修改：移除视图中的排序逻辑 -->
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
          <a href="{{ route('reports.export.grade', ['grade' => $grade_id,
    'date' => $selectedDate ?? $today]) }}" class="btn btn-secondary">
            <i class="fas fa-file-excel"></i> 导出{{ $currentGrade }}Excel
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 按年级分组的图表 -->
<div class="mt-4">
  <canvas id="attendanceChart" width="400" height="200"></canvas>
</div>

@section('scripts')
{{-- 在@section('scripts')中添加 --}}
<script>
document.querySelector('input[name="date"]').addEventListener('change', function() {
    this.closest('form').submit();
});
</script>
<script>
document.querySelector('form').addEventListener('submit', function() {
    this.querySelector('button').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
});
</script>
<script>
function setDate(date) {
    document.querySelector('input[name="date"]').value = date;
    document.querySelector('form').submit();
}
</script>
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
        return $banjis->firstWhere('banji.id', $class->id) 
          ? 'rgba(75, 192, 192, 0.6)'
          : 'rgba(255, 206, 86, 0.6)';
      })->toJson() !!}
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        title: { display: true, text: '人数' }
      },
      x: {
        ticks: {
          autoSkip: false,
          maxRotation: 45,
          minRotation: 45
        }
      }
    }
  }
});
</script>
<style>
<style>
/* 优化样式表 */
.bg-success-light { background-color: #dff0d8 !important; }
.bg-warning-light { background-color: #fcf8e3 !important; }
.table-responsive { min-height: 400px; }
#attendanceChart { border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
</style>
</style>
@endsection
@endsection