@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
     <!-- 添加顶部图片 -->
        <img src="https://www.qhfz.edu.cn/images/ej_bn1.jpg" 
             alt="Banner" 
             class="img-fluid mb-3"
             style="width: 100%; max-width: 1300px; height: 200px;">
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-center">青岛西海岸新区育才初级中学班级量化考核公示</h3>
            
            <div class="card-tools">
                <form method="GET" action="{{ route('quantify.display') }}" class="form-inline">
                    <div class="input-group input-group-sm">
                        {{-- 新增年级选择 --}}
                        <select name="grade_id" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" {{ $selectedGradeId == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="period" class="form-control" onchange="this.form.submit()">
                            <option value="day" {{ $periodType === 'day' ? 'selected' : '' }}>日汇总</option>
                            <option value="week" {{ $periodType === 'week' ? 'selected' : '' }}>周汇总</option>
                            <option value="month" {{ $periodType === 'month' ? 'selected' : '' }}>月汇总</option>
                            <option value="semester" {{ $periodType === 'semester' ? 'selected' : '' }}>学期汇总</option>
                        </select>
                        
                        @if($periodType === 'day')
                            <input type="date" name="day" class="form-control" 
                                   value="{{ $dateRange[0]->toDateString() }}"
                                   max="{{ now()->toDateString() }}">
                        @elseif($periodType === 'week')
                            <input type="week" name="week_start" class="form-control" 
                                   value="{{ $dateRange[0]->format('Y-\WW') }}">
                        @elseif($periodType === 'month')
                            <input type="month" name="month" class="form-control" 
                                   value="{{ $dateRange[0]->format('Y-m') }}">
                        @endif
                        
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-body p-0">
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
                                    <th title="{{ $item->criteria }}">{{ $item->name }} （{{ $item->score }}）</th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    
                    <tbody>
                        {{-- 修改为直接遍历年级班级 --}}
                        @foreach($filteredGrades as $grade)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- 确保正确引入jQuery库 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
