@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- 班级基本信息卡片 -->
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-school fa-2x text-primary me-3"></i>
                            <div>
                                <small class="text-muted">班级名称</small>
                                <h5 class="mb-0 fw-bold">{{ $banji->name ?? '未知班级' }}</h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-chalkboard-teacher fa-2x text-warning me-3"></i>
                            <div>
                                <small class="text-muted">班主任</small>
                                <h5 class="mb-0 fw-bold">{{ $banji->user->name ?? '未指定' }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-user-friends fa-2x text-success me-3"></i>
                            <div>
                                <small class="text-muted">学生人数</small>
                                <h5 class="mb-0 fw-bold">{{ $banji->student_count ?? 0 }}</h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 新增：实到人数 -->
                    <div class="col-md-2">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-user-check fa-2x text-info me-3"></i>
                            <div>
                                <small class="text-muted">实到人数</small>
                                <h5 class="mb-0 fw-bold">
                                    @if(isset($reports) && $reports->isNotEmpty())
                                        {{ $reports->first()->total_actual ?? 0 }}
                                    @else
                                        0
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 新增：缺勤人数 -->
                    <div class="col-md-2">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-user-times fa-2x text-danger me-3"></i>
                            <div>
                                <small class="text-muted">缺勤人数</small>
                                <h5 class="mb-0 fw-bold">
                                    @if(isset($reports) && $reports->isNotEmpty())
                                        {{ $reports->first()->absent_count ?? 0 }}
                                    @else
                                        0
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 双列布局区域 -->
<div class="row g-4">
    <!-- 左侧列 -->
    <div class="col-lg-6">
        <!-- 班级荣誉卡片 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center" style="height: 100%;">
                        <div class="text-white d-flex align-items-center justify-content-center p-3" 
                             style="background: linear-gradient(150deg, #ff9800, #f57c00); min-width: 150px;">
                            <h5 class="card-title m-0"><i class="fas fa-trophy me-2"></i> 班级荣誉</h5>
                        </div>
                        
                        <div class="flex-grow-1" style="overflow: hidden; height: 2em; position: relative;">
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

        <!-- 本班级量化卡片 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-header bg-primary text-white rounded-top" style="background: linear-gradient(135deg, #03a9f4, #0288d1);">
                    <h5 class="card-title m-0"><i class="fas fa-chart-line me-2"></i> 班级量化</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                        <h6 class="text-muted">暂无量化数据</h6>
                        <p class="small text-muted mb-0">量化记录更新后会在此展示</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 右侧列 -->
    <div class="col-lg-6">
        <!-- 通知公告卡片 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center" style="height: 100%;">
                        <div class="text-white d-flex align-items-center justify-content-center p-3" 
                             style="background: linear-gradient(150deg, #ff9800, #f57c00); min-width: 150px;">
                            <h5 class="card-title m-0"><i class="fas fa-bullhorn me-2"></i> 通知公告</h5>
                        </div>
                        
                        <div class="flex-grow-1" style="overflow: hidden; height: 2em; position: relative;">
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

       
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.bg-gradient {
    background: linear-gradient(135deg, #10b981, #059669);
}
.marquee-container {
    position: absolute;
    width: 100%;
}
.topic-list li {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    height: 2em;
    line-height: 2em;
    padding: 0 1rem;
}
</style>
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