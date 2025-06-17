@extends('layouts.app')

@section('title', $user->name . ' 的个人中心')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- 个人信息侧边栏 -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 1.5rem; background: linear-gradient(to bottom, #f8fafc, #ffffff);">
                <div class="card-body p-4 text-center">
                    <!-- 头像悬停放大效果 -->
                    <div class="position-relative d-inline-block mb-3">
                        <img class="rounded-circle border border-3 border-white shadow-sm" 
                             src="{{ $user->avatar }}" 
                             alt="{{ $user->name }}"
                             style="width: 120px; height: 120px; object-fit: cover; transition: transform 0.3s;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle"
                             style="pointer-events: none; border: 3px solid rgba(59, 130, 246, 0.3); opacity: 0; transition: opacity 0.3s;"></div>
                    </div>
                    
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                    
                    <hr class="my-4">
                    
                    <div class="text-start">
                        <h5 class="fw-bold mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>个人简介</h5>
                        <p class="mb-4">{{ $user->introduction ?: '暂无简介' }}</p>
                        
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-calendar-check me-3 text-success"></i>
                            <span>注册于 {{ $user->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock me-3 text-info"></i>
                            <span title="{{ $user->last_actived_at }}">最后活跃 {{ $user->last_actived_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 主体内容区域 -->
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1.5rem;">
                <div class="card-body p-4">
                    <ul class="nav nav-pills justify-content-center mb-4" role="tablist">
                        <li class="nav-item me-2" role="presentation">
                            <a class="nav-link rounded-pill px-4 py-2 {{ active_class(if_query('tab', null)) }}" 
                               href="{{ route('users.show', $user->id) }}" 
                               role="tab">
                                <i class="fas fa-comment-dots me-2"></i>Ta 的话题
                            </a>
                        </li>
                        <li class="nav-item me-2" role="presentation">
                            <a class="nav-link rounded-pill px-4 py-2 {{ active_class(if_query('tab', 'replies')) }}" 
                               href="{{ route('users.show', [$user->id, 'tab' => 'replies']) }}" 
                               role="tab">
                                <i class="fas fa-reply me-2"></i>Ta 的回复
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link rounded-pill px-4 py-2 {{ active_class(if_query('tab', 'assignments')) }}" 
                               href="{{ route('users.show', [$user->id, 'tab' => 'assignments']) }}" 
                               role="tab">
                                <i class="fas fa-book-open me-2"></i>Ta 的作业
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        @if (if_query('tab', 'replies'))
                            @include('users._replies', ['replies' => $user->replies()->with('topic')->recent()->paginate(5)])
                        @elseif (if_query('tab', 'assignments'))
                            @include('users._assignments', ['assignments' => $user->assignments()->recent()->paginate(5)])
                        @else
                            @include('users._topics', ['topics' => $user->topics()->recent()->paginate(5)])
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- 数据统计卡片 -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 1.5rem;">
                        <div class="card-body p-4">
                            <h6 class="text-muted mb-1">话题总数</h6>
                            <h2 class="fw-bold mb-0">{{ $user->topics_count }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 1.5rem;">
                        <div class="card-body p-4">
                            <h6 class="text-muted mb-1">回复总数</h6>
                            <h2 class="fw-bold mb-0">{{ $user->replies_count }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 1.5rem;">
                        <div class="card-body p-4">
                            <h6 class="text-muted mb-1">作业总数</h6>
                            <h2 class="fw-bold mb-0">{{ $user->assignments_count }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 卡片悬停效果 */
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* 头像悬停特效 */
    .position-relative:hover img {
        transform: scale(1.05);
    }
    
    .position-relative:hover .top-0 {
        opacity: 1;
    }
    
    /* 选项卡动画 */
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        transition: all 0.3s ease;
    }
    
    /* 内容区域阴影 */
    .tab-content {
        min-height: 200px;
    }
    
    /* 响应式调整 */
    @media (max-width: 768px) {
        .nav-pills .nav-link {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    }
</style>
@endsection