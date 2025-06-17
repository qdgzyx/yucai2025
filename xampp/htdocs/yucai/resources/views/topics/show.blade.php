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

          {{-- 操作区域 - 增加微交互动效 --}}
          @can('update', $topic)
            <div class="mt-5 pt-4 border-top">
              <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('topics.edit', $topic->id) }}" 
                   class="btn btn-outline-primary btn-lg px-4 rounded-pill d-flex align-items-center gap-2"
                   style="transition: transform 0.2s;">
                  <i class="far fa-edit"></i>
                  <span>编辑文章</span>
                </a>
                <form action="{{ route('topics.destroy', $topic->id) }}" method="post">
                  @csrf
                  @method('DELETE')
                  <button type="submit" 
                          class="btn btn-danger btn-lg px-4 rounded-pill d-flex align-items-center gap-2"
                          style="transition: transform 0.2s;"
                          onclick="return confirm('确定要删除吗？')">
                    <i class="far fa-trash-alt"></i>
                    <span>删除文章</span>
                  </button>
                </form>
              </div>
            </div>
          @endcan
        </div>
      </div>

      {{-- 回复区域 - 增加折叠动画效果 --}}
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-4">
          @includeWhen(Auth::check(), 'topics._reply_box', ['topic' => $topic])
          @include('topics._reply_list', ['replies' => $topic->replies()->with('user')->get()])
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #1e40af, #1e3a8a);
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