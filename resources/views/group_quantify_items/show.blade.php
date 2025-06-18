@extends('layouts.app3')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>小组量化项目详情</h2>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">项目名称</label>
                <p>{{ $group_quantify_item->name }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label">评分标准</label>
                <p>{{ $group_quantify_item->criteria }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label">分值</label>
                <p>{{ $group_quantify_item->score }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label">类型</label>
                <p>{{ $group_quantify_item->type }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('group_quantify_items.edit', $group_quantify_item) }}" class="btn btn-primary">编辑</a>
                <a href="{{ route('group_quantify_items.index') }}" class="btn btn-secondary">返回列表</a>
            </div>
        </div>
    </div>
</div>
@endsection