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
    // 添加资源加载完成检测
    window.addEventListener('load', function() {
        console.log('Window load 事件触发');
        
        // 使用延迟执行确保元素存在
        setTimeout(() => {
            // 增加Chart.js可用性检测
            if (typeof Chart === 'undefined') {
                console.error('Chart.js未正确加载');
                const diag = document.createElement('div');
                diag.className = 'alert alert-danger';
                diag.innerHTML = '<strong>图表库加载失败</strong><br>请检查：<ul>'+
                    '<li>网络连接状态</li>'+
                    '<li>CND资源可用性</li>'+
                    '<li>浏览器控制台错误信息</li></ul>';
                document.body.appendChild(diag);
                return;
            }
            
            const ctx = document.getElementById('groupQuantifyChart');
            
            // 添加元素存在性双重验证
            if (!ctx || !ctx.getContext) {
                console.error('图表容器无效或不存在');
                // 创建诊断元素
                const diag = document.createElement('div');
                diag.className = 'alert alert-warning';
                diag.innerHTML = '<strong>图表容器异常</strong><br>可能原因：<ul>'+
                    '<li>DOM未正确加载</li>'+
                    '<li>CSS选择器冲突</li>'+
                    '<li>脚本加载时机过早</li></ul>';
                document.body.appendChild(diag);
                return;
            }

            console.log('开始初始化图表，Canvas尺寸:', ctx.width, 'x', ctx.height);
            
            // 强制设置尺寸
            ctx.width = 600;
            ctx.height = 400;
            
            // 验证数据生成
            const groups = Array.from({length: 9}, (_, i) => '小组' + (i+1));
            const scores = Array.from({length: 9}, () => Math.floor(Math.random() * 100));
            console.log('生成数据:', {groups, scores});

            try {
                // 创建图表配置
                const config = {
                    type: 'bar',
                    data: {
                        labels: groups,
                        datasets: [{
                            label: '量化分数',
                            data: scores,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: '小组量化情况',
                                font: { size: 16 }
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
                };

                // 尝试创建图表实例
                const myChart = new Chart(ctx.getContext('2d'), config);
                console.log('图表实例创建成功:', myChart);
                
                // 添加图表状态检查定时器
                setInterval(() => {
                    if (myChart.ctx.canvas.width === 0) {
                        console.warn('检测到图表容器尺寸异常归零');
                    }
                }, 2000);
                
            } catch (chartError) {
                console.error('图表初始化失败:', chartError);
                // 显示详细错误
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger';
                errorDiv.innerHTML = `
                    <h6>图表初始化失败</h6>
                    <pre>${chartError.stack}</pre>
                `;
                ctx.parentNode.appendChild(errorDiv);
            }
        }, 2000); // 延迟2秒执行
    });
</script>
@endsection