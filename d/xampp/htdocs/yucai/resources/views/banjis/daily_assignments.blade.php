<div class="card border-0 shadow-sm" style=" margin: 2rem auto; background: #f8fafc;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-gray">
                    <tr>
                        <th style="width:15%">学科</th>
                        <th style="width:55%">作业内容</th>
                        <th class="text-center" style="width:15%">截止时间</th>
                        <th class="text-center" style="width:15%">教师</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedAssignments as $subject => $assignments)
                        @foreach($assignments as $assignment)
                        <tr>
                            <td>{{ $subject }}</td>
                            <td>
                                <div class="d-flex flex-column">  
                                    <span class="text-dark">{!!$assignment->content!!}</span>
                                    @if($assignment->attachment)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/'.$assignment->attachment) }}" 
                                           class="btn btn-sm btn-outline-secondary px-2 py-1"
                                           title="下载附件">
                                            <i class="bi bi-paperclip me-1"></i>附件
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge {{ $assignment->deadline->isPast() ? 'bg-soft-red text-danger' : 'bg-soft-green text-success' }}">
                                    {{ $assignment->deadline->format('Y/m/d H:i') }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="text-muted">{{ $assignment->user->name }}</span>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="bi bi-check-circle display-6 text-success mb-3"></i>
                            <p class="text-muted mb-0">今日无作业任务</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
```

<style>
    /* 添加新的样式规则 */
    .table {
        border-collapse: collapse;
    }
    .table thead tr th,
    .table tbody tr td {
        padding: 0.5rem; /* 调整单元格内边距 */
    }
    .table thead tr th:last-child,
    .table tbody tr td:last-child {
        padding-right: 0; /* 右侧单元格去除右侧内边距 */
    }
    /* 移除表格顶部的多余间距 */
    .table thead tr:first-child th {
        padding-top: 0;
    }
</style>