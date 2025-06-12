@extends('layouts.app')

@section('content')
<div class="container">
    <!-- 将标题和按钮合并为一行显示 -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">班级小组管理</h2>
        <a href="{{ route('group-basic-infos.create') }}" class="btn btn-primary">新建小组</a>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>序号</th>
                <th>小组名称</th>
                <th>所属班级</th>
                <th>组长</th>
                <th>组员</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td>{{ $group->name }}</td>
                <td>{{ $group->banji->name ?? '' }}</td>
                <td>{{ $group->leader }}</td>
                <td>{{ $group->members }}</td>
                <td>
                    <a href="{{ route('group-basic-infos.edit', $group) }}" class="btn btn-sm btn-primary">编辑</a>
                    <form action="{{ route('group-basic-infos.destroy', $group) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">删除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection