@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- 班级基本信息 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title">班级基本信息</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>班级名称：</strong> {{ $banji->name }}
                        </div>
                        <div class="col-md-4">
                            <strong>年级：</strong> {{ $banji->grade }}
                        </div>
                        <div class="col-md-4">
                            <strong>班主任：</strong> {{ $banji->teacher->name ?? '未指定' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 班级荣誉 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title">班级荣誉</h4>
                </div>
                <div class="card-body">
                    @if($honors->isEmpty())
                        <p>暂无班级荣誉记录。</p>
                    @else
                        <ul class="list-group">
                            @foreach($honors as $honor)
                                <li class="list-group-item">
                                    <strong>{{ $honor->title }}</strong> - {{ $honor->description }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- 通知公告 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="card-title">通知公告</h4>
                </div>
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <p>暂无通知公告。</p>
                    @else
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">
                                    <strong>{{ $notification->title }}</strong> - {{ $notification->content }}
                                    <small class="text-muted float-right">{{ $notification->created_at->format('Y-m-d') }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- 本班级量化 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title">本班级量化</h4>
                </div>
                <div class="card-body">
                    @if($classQuantifications->isEmpty())
                        <p>暂无班级量化记录。</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>事项</th>
                                    <th>分数</th>
                                    <th>记录时间</th>
                                    <th>记录人</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classQuantifications as $quantification)
                                    <tr>
                                        <td>{{ $quantification->content }}</td>
                                        <td>{{ $quantification->score }}</td>
                                        <td>{{ $quantification->time->format('Y-m-d H:i') }}</td>
                                        <td>{{ $quantification->recorder }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- 本班级小组量化 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4 class="card-title">本班级小组量化</h4>
                </div>
                <div class="card-body">
                    @if($groupQuantifications->isEmpty())
                        <p>暂无小组量化记录。</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>小组</th>
                                    <th>事项</th>
                                    <th>分数</th>
                                    <th>记录时间</th>
                                    <th>记录人</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupQuantifications as $quantification)
                                    <tr>
                                        <td>{{ $quantification->groupBasicInfo->name ?? '未知小组' }}</td>
                                        <td>{{ $quantification->content }}</td>
                                        <td>{{ $quantification->score }}</td>
                                        <td>{{ $quantification->time->format('Y-m-d H:i') }}</td>
                                        <td>{{ $quantification->recorder }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- 出勤信息公示 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="card-title">出勤信息公示</h4>
                </div>
                <div class="card-body">
                    @if($attendanceRecords->isEmpty())
                        <p>暂无出勤记录。</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>学生姓名</th>
                                    <th>状态</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $record)
                                    <tr>
                                        <td>{{ $record->date->format('Y-m-d') }}</td>
                                        <td>{{ $record->student->name }}</td>
                                        <td>{{ $record->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- 作业公示 -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title">作业公示</h4>
                </div>
                <div class="card-body">
                    @if($assignments->isEmpty())
                        <p>暂无作业记录。</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>科目</th>
                                    <th>作业内容</th>
                                    <th>截止日期</th>
                                    <th>状态</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->subject->name }}</td>
                                        <td>{{ $assignment->content }}</td>
                                        <td>{{ $assignment->due_date->format('Y-m-d') }}</td>
                                        <td>{{ $assignment->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection