@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">小组量化项目管理</h2>
        <a href="{{ route('group_quantify_items.create') }}" class="btn btn-primary">新建项目</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>项目名称</th>
                <th>分值</th>
                <th>类型</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group_quantify_items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->score }}</td>
                <td>{{ $item->type }}</td>
                <td>
                    <a href="{{ route('group_quantify_items.show', $item) }}" class="btn btn-sm btn-info">查看</a>
                    <a href="{{ route('group_quantify_items.edit', $item) }}" class="btn btn-sm btn-primary">编辑</a>
                    <form action="{{ route('group_quantify_items.destroy', $item) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">删除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $group_quantify_items->links() }}
</div>
@endsection