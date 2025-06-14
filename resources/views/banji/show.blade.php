@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- 班级基本信息 -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <!-- <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #1a73e8, #0d47a1);">
                <h4 class="card-title"><i class="fas fa-users mr-2"></i>班级基本信息</h4>
            </div> -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="fas fa-school mr-1"></i> 班级名称：</strong> {{ $banji->name ?? '未知班级' }}
                    </div>
                    <div class="col-md-4">
                        <strong>年级：</strong> {{ $banji->grade->name ?? '未知年级' }}
                    </div>
                    <div class="col-md-4">
                        <strong>班主任：</strong> {{ $banji->user->name ?? '未指定' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 

<!-- 双列布局区域 -->
<div class="row">
    <!-- 左侧列 -->
    <div class="col-lg-6">
        <!-- 班级荣誉 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center" style="height: 100%;">
                        <!-- 左侧固定标题 -->
                        <div class="text-white d-flex align-items-center justify-content-center p-3" 
                             style="background: linear-gradient(150deg, #ff9800, #f57c00); min-width: 150px;">
                            <h4 class="card-title m-0"><i class="fas fa-trophy mr-2"></i> 班级荣誉</h4>
                        </div>
                        
                        <!-- 右侧滚动内容：修改为单行自动滚动 -->
                        <div class="flex-grow-1" style="overflow: hidden; height: 1.5em; position: relative;">
                            <div class="marquee-container">
                                <ul class="topic-list list-group list-group-flush m-0" style="animation: scrollAnnouncements 15s linear infinite;">
                                    @include('topics._topic_lista', ['topics' => $topics])
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <!-- 本班级量化 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #03a9f4, #0288d1);">
                    <h4 class="card-title m-0"><i class="fas fa-chart-line mr-2"></i> 班级量化</h4>
                </div>
                <div class="card-body p-0">
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
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                    <h5 class="text-muted">暂无量化数据</h5>
                                    <p class="small text-muted mb-0">量化记录更新后会在此展示</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 本班级小组量化 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #78909c, #546e7a);">
                    <h4 class="card-title m-0"><i class="fas fa-layer-group mr-2"></i> 小组量化</h4>
                </div>
                <div class="card-body p-0">
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
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                    <h5 class="text-muted">暂无小组量化数据</h5>
                                    <p class="small text-muted mb-0">小组活动记录会在此展示</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 右侧列 -->
    <div class="col-lg-6">
        <!-- 通知公告修改：调整为班级荣誉样式 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center" style="height: 100%;">
                        <!-- 左侧固定标题 -->
                        <div class="text-white d-flex align-items-center justify-content-center p-3" 
                             style="background: linear-gradient(150deg, #ff9800, #f57c00); min-width: 150px;">
                            <h4 class="card-title m-0"><i class="fas fa-bullhorn mr-2"></i> 通知公告</h4>
                        </div>
                        
                        <!-- 右侧滚动内容 -->
                        <div class="flex-grow-1" style="overflow: hidden; height: 1.5em; position: relative;">
                            <div class="marquee-container">
                                <ul class="topic-list list-group list-group-flush m-0" style="animation: scrollAnnouncements 15s linear infinite;">
                                    @include('topics._topic_lista', ['topics' => $topics])
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 出勤信息公示 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #ef5350, #d32f2f);">
                    <h4 class="card-title m-0"><i class="fas fa-calendar-check mr-2"></i> 出勤公示</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>日期</th>
                                <th>学生姓名</th>
                                <th>状态</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                    <h5 class="text-muted">暂无出勤记录</h5>
                                    <p class="small text-muted mb-0">出勤情况更新后会在此公示</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 作业公示 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #5d4037, #3e2723);">
                    <h4 class="card-title m-0"><i class="fas fa-book mr-2"></i> 作业公示</h4>
                </div>
                <div class="card-body p-0">
                    <li class="list-group-item text-left py-4">
                        @include('banjis.daily_assignments', [
                            'assignments' => $assignments,
                            'date' => $date
                        ])   
                    </li> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* 添加滚动动画 */
    @keyframes scrollAnnouncements {
        0% { transform: translateY(0); }
        100% { transform: translateY(-100%); }
    }
    
    .marquee-container {
        position: absolute;
        width: 100%;
    }
    
    /* 限制为单行显示 */
    .topic-list li {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 1.5em;
        line-height: 1.5em;
    }
    
    /* 确保内容区表格正确显示 */
    .flex-grow-1 table {
        width: 100%;
        margin: 0;
    }
</style>
@endpush