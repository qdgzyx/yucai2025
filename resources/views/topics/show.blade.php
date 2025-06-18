@extends('layouts.app')

@section('title', $topic->title)
@section('description', $topic->excerpt)

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
      {{-- 主体卡片 - 增加立体感和渐变背景 --}}
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-5">
        {{-- 标题区域 - 增加渐变色背景 --}}
        <div class="card-header bg-gradient text-white py-4">
          <h1 class="mb-0 text-center fs-2 fw-bold">{{ $topic->title }}</h1>
        </div>

        <div class="card-body p-5">
          {{-- 元信息区域 - 使用徽章样式优化视觉呈现 --}}
          <div class="d-flex flex-wrap justify-content-center gap-3 mb-4 pb-3 border-bottom">
            <div class="d-flex align-items-center text-muted">
              <i class="fas fa-user-circle text-primary me-2"></i>
              <span class="fw-medium">{{ $topic->user->name }}</span>
            </div>
            <div class="d-flex align-items-center text-muted">
              <i class="fas fa-clock text-success me-2"></i>
              <span>{{ $topic->created_at->diffForHumans() }}</span>
            </div>
            <div class="d-flex align-items-center text-muted">
              <i class="fas fa-eye text-info me-2"></i>
              <span>{{ $topic->visits()->count() }} 阅读</span>
            </div>
          </div>

          {{-- 正文区域 - 优化段落间距和字体排版 --}}
          <div class="topic-body prose max-w-none">
            {!! $topic->body !!}
          </div>

          
        </div>
      </div>
{{-- 用户回复列表 --}}
      <div class="card topic-reply mt-4">
          <div class="card-body">
              @includeWhen(Auth::check(), 'topics._reply_box', ['topic' => $topic])
              @include('topics._reply_list', ['replies' => $topic->replies()->with('user')->get()])
          </div>
      </div>
      
    </div>
  </div>
</div>


<style>
.bg-gradient {
    background: linear-gradient(135deg, #1e40af, #1e3a8a) !important; /* 增加 !important 提高优先级 */
    color: white !important; /* 确保标题文本颜色为白色 */
}

.prose {
    font-size: 1.1rem;
    line-height: 1.8;
}

.prose p {
    margin-bottom: 1.5rem;
}

.prose img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-2px);
}

.btn:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.5);
}
</style>

@endsection