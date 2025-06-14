@if (count($topics))
  <ul class="list-unstyled">
    @foreach ($topics as $topic)
      <li class="d-flex">
        
        <div class="flex-grow-1 ms-2">
          <div class="mt-0 mb-1 text-left">
            {{-- 添加序号和发布时间 --}}
            <span class="text-muted small">{{ $loop->iteration }}. </span>
            <a href="{{ $topic->link() }}" title="{{ $topic->title }}">
              {{ $topic->title }}
            </a>
            <span class="text-muted small">({{ $topic->created_at->format('Y-m-d H:i') }})</span>
          </div>
        </div>
      </li>

      @if ( ! $loop->last)
        <hr>
      @endif
    @endforeach
  </ul>
@else
  <div class="empty-block">暂无数据 ~_~ </div>
@endif