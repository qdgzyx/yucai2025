@extends('layouts.app3')

@section('content')
<div class="container">
    <h2>{{ isset($group_quantify_item) ? '编辑小组量化项目' : '新建小组量化项目' }}</h2>
    <form method="POST" action="{{ isset($group_quantify_item) ? route('group_quantify_items.update', $group_quantify_item) : route('group_quantify_items.store') }}">
        @csrf
        @if(isset($group_quantify_item))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">项目名称</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group_quantify_item->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="criteria" class="form-label">评分标准</label>
            <textarea class="form-control" id="criteria" name="criteria" rows="3" required>{{ old('criteria', $group_quantify_item->criteria ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="score" class="form-label">分值</label>
            <input type="number" class="form-control" id="score" name="score" value="{{ old('score', $group_quantify_item->score ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">类型</label>
            <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $group_quantify_item->type ?? '') }}">
        </div>

        <button type="submit" class="btn btn-primary">提交</button>
    </form>
</div>
@endsection