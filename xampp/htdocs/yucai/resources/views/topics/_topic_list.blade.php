@if (count($topics))
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        @foreach ($topics as $topic)
          <li class="list-group-item px-0 py-3">
            <div class="d-flex align-items-center">
              <!-- 用户头像区域 -->
              <div class="flex-shrink-0 me-3">
                <a href="{{ route('users.show', [$topic->user_id]) }}" class="text-decoration-none">
                  <img class="rounded-circle border border-2 border-primary-subtle" 
                       style="width: 48px; height: 48px;" 
                       src="{{ $topic->user->avatar }}" 
                       title="{{ $topic->user->name }}">
                </a>
              </div>

              <!-- 内容主体 -->
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <!-- 话题标题 -->
                  <h5 class="mb-0">
                    <a href="{{ $topic->link() }}" 
                       class="text-decoration-none text-dark fw-medium"
                       title="{{ $topic->title }}">
                      {{ $topic->title }}
                    </a>
                  </h5>
                  
                  <!-- 回复数徽章 -->
                  <span class="badge bg-light text-secondary ms-2">
                    {{ $topic->reply_count }} 回复
                  </span>
                </div>

                <!-- 元信息行 -->
                <div class="d-flex flex-wrap align-items-center text-muted small">
                  <!-- 分类标签 -->
                  <div class="d-flex align-items-center me-3 mb-1">
                    <i class="far fa-folder text-muted me-1"></i>
                    <a href="{{ route('categories.show', $topic->category_id) }}" 
                       class="text-decoration-none text-secondary"
                       title="{{ $topic->category->name }}">
                      {{ $topic->category->name }}
                    </a>
                  </div>
                  
                  <!-- 作者信息 -->
                  <div class="d-flex align-items-center me-3 mb-1">
                    <i class="far fa-user text-muted me-1"></i>
                    <a href="{{ route('users.show', [$topic->user_id]) }}" 
                       class="text-decoration-none text-secondary"
                       title="{{ $topic->user->name }}">
                      {{ $topic->user->name }}
                    </a>
                  </div>
                  
                  <!-- 更新时间 -->
                  <div class="d-flex align-items-center mb-1">
                    <i class="far fa-clock text-muted me-1"></i>
                    <span class="timeago" 
                          title="最后活跃于：{{ $topic->updated_at }}">
                      {{ $topic->updated_at->diffForHumans() }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

@else
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-5">
      <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
      <p class="text-muted mb-0">暂无话题数据</p>
    </div>
  </div>
@endif