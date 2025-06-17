@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-center">青岛西海岸新区育才初级中学小组量化公示</h3>
            
            <div class="card-tools">
                <form method="GET" action="{{ route('group_quantify.display') }}" class="form-inline">
                    <div class="input-group input-group-sm">
                        {{-- 班级选择 --}}
                        <select name="banji_id" class="form-control mr-2" onchange="this.form.submit()">
                            @foreach($banjis as $banji)
                                <option value="{{ $banji->id }}" {{ $selectedBanjiId == $banji->id ? 'selected' : '' }}>
                                    {{ $banji->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        {{-- 周期选择 --}}
                        <select name="period" class="form-control" onchange="this.form.submit()">
                            <option value="day" {{ $periodType === 'day' ? 'selected' : '' }}>日汇总</option>
                            <option value="week" {{ $periodType === 'week' ? 'selected' : '' }}>周汇总</option>
                            <option value="month" {{ $periodType === 'month' ? 'selected' : '' }}>月汇总</option>
                            <option value="semester" {{ $periodType === 'semester' ? 'selected' : '' }}>学期汇总</option>
                        </select>
                        
                        {{-- 日期选择器 --}}
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
                            <th>小组</th>
                            @foreach($quantifyItems as $type => $items)
                                @foreach($items as $item)
                                    <th title="{{ $item->criteria }}">{{ $item->name }} （{{ $item->score }}）</th>
                                @endforeach
                            @endforeach
                            <th>合计</th>
                            <th>排名</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        {{-- 修改视图中的遍历逻辑以匹配新的数组结构 --}}
                        @foreach($groupQuantifyData as $row)
                            <tr>
                                <td>{{ $row['group']->name }} ({{ $row['group']->banji->name ?? '' }})</td>
                                
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
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection