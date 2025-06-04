@extends('layouts.app')

@section('title', $topic->title)
@section('description', $topic->excerpt)

@section('content')

  <div class="row">

    {{-- 扩展主内容区域为全宽 --}}
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 topic-content">
      <div class="card shadow-sm">
        <div class="card-body">
          {{-- 优化标题区域 --}}
          <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-primary mb-3">
              {{ $topic->title }}
            </h1>
            <div class="d-flex justify-content-center align-items-center text-muted">
              <span class="me-3">
                <i class="fas fa-user-circle me-1"></i>{{ $topic->user->name }}
              </span>
              <span class="me-3">
                <i class="fas fa-clock me-1"></i>{{ $topic->created_at->diffForHumans() }}
              </span>
              <span>
                <i class="fas fa-comments me-1"></i>{{ $topic->reply_count }} 条回复
              </span>
            </div>
            <hr class="w-25 mx-auto my-4">
          </div>

          {{-- 优化正文样式 --}}
          <div class="topic-body fs-5 lh-base text-gray-800">
            {!! $topic->body !!}
          </div>

          {{-- 美化操作按钮 --}}
          @can('update', $topic)
            <div class="operate mt-5">
              <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('topics.edit', $topic->id) }}" class="btn btn-primary btn-lg px-4">
                  <i class="far fa-edit me-2"></i>编辑文章
                </a>
                <form action="{{ route('topics.destroy', $topic->id) }}" method="post">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button type="submit" class="btn btn-danger btn-lg px-4" onclick="return confirm('确定要删除吗？')">
                    <i class="far fa-trash-alt me-2"></i>删除文章
                  </button>
                </form>
              </div>
            </div>
          @endcan

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
@stop
