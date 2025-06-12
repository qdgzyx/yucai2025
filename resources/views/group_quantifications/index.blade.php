@extends('layouts.app')

@section('content')
<div class="container">
    <h2>班级小组积分公示</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>序号</th>
                <th>小组名称</th>
                <th>所属班级</th>
                <th>事项内容</th>
                <th>分数</th>
                <th>记录时间</th>
                <th>记录人</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quantifications as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->groupBasicInfo->name ?? '' }}</td> <!-- 改为显示关联小组名称 -->
                <td>{{ $item->groupBasicInfo->banji->name ?? '' }}</td> <!-- 通过小组获取班级名称 -->
                <td>{{ $item->content }}</td>
                <td>{{ $item->score }}</td>
                <td>{{ $item->time->format('Y-m-d H:i') }}</td>
                <td>{{ $item->recorder }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection