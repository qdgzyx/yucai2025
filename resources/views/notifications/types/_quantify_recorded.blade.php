<li class="media @if (! $loop->last) border-bottom @endif">
  

  <div class="media-body">
    <div class="media-heading mt-0 mb-1 text-secondary">
      <strong>量化记录通知</strong>
      <span class="meta float-right" title="{{ \Carbon\Carbon::parse($notification->data['created_at'] ?? now()) }}">
        <i class="far fa-clock"></i>
        {{ \Carbon\Carbon::parse($notification->data['created_at'] ?? now())->diffForHumans() }}
      </span>
    </div>
    <div class="notification-content">
      <p>量化记录已创建，详情如下：</p>
      <ul>
        <li>量化记录 ID: {{ $notification->data['quantify_id'] ?? '未知' }}</li>
         <li>量化记录 ID: {{ $notification->data['banji_name'] ?? '未知' }}</li>
        <li>量化记录内容: {{ $notification->data['score'] ?? '无内容' }}</li>
      </ul>
    </div>
  </div>
</li>