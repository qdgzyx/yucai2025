@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    学期量化报表 - {{ $currentSemester->name }}
                </h3>
                
                <div class="form-group">
                    <select class="form-control" id="grade-select" onchange="updateGradeData(this.value)">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- 月份导航 -->
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    @foreach($months as $month)
                        <button class="btn btn-outline-primary month-btn" 
                                data-month="{{ $month['value'] }}"
                                onclick="loadMonthData('{{ $month['value'] }}')">
                            {{ $month['name'] }}
                        </button>
                    @endforeach
                </div>
            </div>
            
            <!-- 班级数据表格 -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2">班级</th>
                            @foreach($quantifyItems as $type => $items)
                                <th colspan="{{ count($items) }}" class="text-center">{{ $type }}</th>
                            @endforeach
                            <th rowspan="2">合计</th>
                            <th rowspan="2">排名</th>
                        </tr>
                        <tr>
                            @foreach($quantifyItems as $type => $items)
                                @foreach($items as $item)
                                    <th title="{{ $item->criteria }}">{{ $item->name }}</th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    
                    <tbody id="grade-data">
                        @foreach($grades as $grade)
                            <tr class="grade-header bg-gray-100">
                                <td colspan="{{ array_reduce($quantifyItems->toArray(), function($carry, $items) { return $carry + count($items); }, 0) + 3 }}" 
                                    data-toggle="collapse" href="#grade-{{ $grade->id }}" 
                                    style="cursor: pointer">
                                    <strong>{{ $grade->name }}</strong>
                                    <i class="fas fa-chevron-down float-right"></i>
                                </td>
                            </tr>
                            
                            <tbody id="grade-{{ $grade->id }}" class="collapse show">
                                @foreach($quantifyData[$grade->id] ?? [] as $banjiId => $row)
                                    <tr>
                                        <td>{{ $row['banji']->name }}</td>
                                        
                                        @foreach($quantifyItems as $type => $items)
                                            @foreach($items as $item)
                                                <td class="text-center">{{ $row['items'][$type][$item->id] ?? 0 }}</td>
                                            @endforeach
                                        @endforeach
                                        
                                        <td class="text-center font-weight-bold">{{ $row['total'] }}</td>
                                        <td class="text-center">{{ $row['rank'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // 折叠/展开年级数据
    $('.grade-header').click(function() {
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });
});

function updateGradeData(gradeId) {
    // AJAX加载年级数据
    $.get(`/api/quantify/semester-report?grade_id=${gradeId}`, function(data) {
        $('#grade-data').html(data);
    });
}

function loadMonthData(month) {
    const gradeId = $('#grade-select').val();
    // AJAX加载月份数据
    $.get(`/api/quantify/semester-report?grade_id=${gradeId}&month=${month}`, function(data) {
        $('#grade-data').html(data);
    });
}
</script>
@endsection
@endsection