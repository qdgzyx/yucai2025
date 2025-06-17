@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- 班级基本信息卡片 -->
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-2">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-school fa-2x text-primary me-3"></i>
                            <div>
                                <small class="text-muted">班级名称</small>
                                <h5 class="mb-0 fw-bold">{{ $banji->name ?? '未知班级' }}</h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="col-md-2">
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-chalkboard-teacher fa-2x text-warning me-3"></i>
                            <div>
                                <small class="text-muted">班主任</small>
                                <h5 class="mb-0 fw-bold">{{ $banji->user->name ?? '未指定' }}</h5>
                            </div>
                        </div>
                    </div> -->

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
                    <div class="col-md-2">  <!-- 修改：col-md-2 改为 col-md-1 -->
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

                    <!-- 新增：周名次排名 -->
                    <div class="col-md-2">  <!-- 新增卡片 -->
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-trophy fa-2x text-warning me-3"></i>
                            <div>
                                <small class="text-muted">周名次排名</small>
                                <h5 class="mb-0 fw-bold">
                                    {{ $weeklyRank ?? 'N/A' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 新增：日排名 -->
                    <div class="col-md-2">  <!-- 新增卡片 -->
                        <div class="d-flex align-items-center bg-light p-3 rounded-3">
                            <i class="fas fa-calendar-day fa-2x text-purple me-3"></i>
                            <div>
                                <small class="text-muted">日排名</small>
                                <h5 class="mb-0 fw-bold">
                                    {{ $dailyRank ?? 'N/A' }}
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
        <!-- 恢复班级量化卡片 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-header bg-primary text-white rounded-top" style="background: linear-gradient(135deg, #03a9f4, #0288d1);">
                    <h5 class="card-title m-0"><i class="fas fa-chart-line me-2"></i> 小组量化</h5>
                </div>
                <div class="card-body p-0">
                    <!-- 添加图表容器 -->
                    <div class="p-3">
                        <canvas id="groupQuantifyChart" style="height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 右侧列 -->
    <div class="col-lg-6">
        <!-- 恢复作业公示卡片 -->
        <div class="col-md-12 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-header text-white rounded-top" style="background: linear-gradient(135deg, #5d4037, #3e2723);">
                    <h5 class="card-title m-0"><i class="fas fa-book me-2"></i> 作业公示</h5>
                </div>
                <div class="card-body p-0">
                    @include('banjis.daily_assignments', [
                        'groupedAssignments' => $assignments ?? [],
                        'date' => $date ?? now()->format('Y-m-d')
                    ])
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

    /* 新增：图表容器样式 */
    #groupQuantifyChart {
        width: 100% !important;
    }
</style>
@endpush

@section('scripts')
<!-- 引入Chart.js库 -->
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    window.addEventListener('load', function() {
        const ctx = document.getElementById('groupQuantifyChart');
        
        // 增强错误处理
        if (!ctx || !ctx.getContext) {
            showErrorMessage('图表容器初始化失败');
            return;
        }

        // 使用内嵌数据初始化图表
        function initChart() {
            // 从PHP传递的变量获取数据
            const data = @json($groupScores);
            
            if (!data || data.length === 0) {
                showErrorMessage('暂无今日量化数据');
                return;
            }

            const groups = data.map(item => item.group_name);
            const scores = data.map(item => item.total_score);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: groups,
                    datasets: [{
                        label: '今日量化分数',
                        data: scores,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: '各小组今日量化分数合计',
                            font: { size: 16 }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `分数: ${ctx.raw}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: '分数' }
                        },
                        x: {
                            title: { display: true, text: '小组' }
                        }
                    }
                }
            });
        }

        // 执行初始化
        try {
            initChart();
        } catch (error) {
            console.error('图表初始化失败:', error);
            showErrorMessage('图表渲染失败');
        }

        function showErrorMessage(msg) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger mt-3';
            alert.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${msg}`;
            ctx.parentNode.appendChild(alert);
        }
    });
</script>
@endsection
