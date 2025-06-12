@extends('layouts.app')

@section('content')
<div class="container">
    <h2>添加小组量化积分</h2>
    <form method="POST" action="{{ route('group_quantifications.store') }}">
        @csrf
        <div class="card mb-4">
            <div class="card-body">
                {{-- 修改：将输入框改为下拉选择框 --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <select class="form-control" name="quantification[content]" required>
                            <option value="">请选择事项内容</option>
                            @foreach($quantifyItems as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="datetime-local" class="form-control" 
                               name="quantification[time]" 
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>

                @foreach($groups as $group)
                <div class="row mb-3 group-item">
                    <div class="col-md-2">
                        <h5>{{ $group->name }}{{ $group->leader }}</h5>
                        <small class="text-muted">{{ $group->banji->name }}</small>
                    </div>
                    <div class="col-md-8">
                        <input type="hidden" name="quantifications[{{ $loop->index }}][group_id]" value="{{ $group->id }}">
                        
                        {{-- 删除原有内容输入框 --}}
                        {{-- <div class="mb-3">...</div> --}}
                        
                        <div class="row">
                            <div class="col-md-12"> {{-- 修改列宽为全宽 --}}
                                <input type="number" class="form-control" 
                                       name="quantifications[{{ $loop->index }}][score]" 
                                       placeholder="分数" required>
                            </div>
                            {{-- 删除原有时间输入框 --}}
                            {{-- <div class="col-md-8">...</div> --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">提交所有量化记录</button>
            <a href="{{ route('group_quantifications.index') }}" class="btn btn-secondary">返回列表</a>
        </div>
    </form>
</div>
@endsection