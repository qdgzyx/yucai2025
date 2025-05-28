@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">量化公示 - {{ $currentSemester->name }}</h3>
            
            <div class="card-tools">
                <form method="GET" action="{{ route('quantify.display') }}" class="form-inline">
                    <div class="input-group input-group-sm">
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
                                    <th title="{{ $item->criteria }}">{{ $item->name }}</th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    
                    <tbody>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // 折叠/展开年级数据
    $('.grade-header').click(function() {
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });
});
</script>
@endsection