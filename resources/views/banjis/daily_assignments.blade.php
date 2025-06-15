{{-- 优化作业表格样式与交互体验 --}}
<div class="card border-0 shadow-sm" style="margin: 2rem auto; border-radius: 1.5rem;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <tr>
                        <th style="width:15%" class="py-3"><i class="fas fa-book-reader me-2"></i> 学科</th>
                        <th style="width:55%" class="py-3"><i class="fas fa-file-alt me-2"></i> 作业内容</th>
                        <th class="text-center" style="width:15%"><i class="fas fa-clock me-2"></i> 截止时间</th>
                        <th class="text-center" style="width:15%"><i class="fas fa-chalkboard-teacher me-2"></i> 教师</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedAssignments as $subject => $assignments)
                        @foreach($assignments as $assignment)
                        <tr class="hover-zoom" style="transition: transform 0.2s;">
                            <td class="fw-bold text-primary">{{ $subject }}</td>
                            <td>
                                <div class="d-flex flex-column">  
                                    {{-- 添加文本省略与悬浮提示 --}}
                                    <span class="text-dark" title="{{ $assignment->content }}">{{ Str::limit($assignment->content, 50) }}</span>
                                    @if($assignment->attachment)
                                    <div class="mt-1">
                                        {{-- 优化下载按钮样式 --}}
                                        <a href="{{ asset('storage/'.$assignment->attachment) }}" 
                                           class="btn btn-sm btn-outline-secondary px-2 py-1 d-flex align-items-center gap-1"
                                           title="下载附件">
                                            <i class="bi bi-paperclip"></i> 附件
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                {{-- 增强时间状态视觉反馈 --}}
                                <span class="badge {{ $assignment->deadline->isPast() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} d-flex align-items-center gap-1">
                                    @if($assignment->deadline->isPast())
                                        <i class="fas fa-exclamation-triangle"></i>
                                    @else
                                        <i class="fas fa-clock"></i>
                                    @endif
                                    {{ $assignment->deadline->format('Y/m/d H:i') }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                {{-- 教师名称添加头像展示 --}}
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $assignment->user->avatar ?? asset('images/default-avatar.png') }}" 
                                         class="rounded-circle me-2" 
                                         width="24" height="24" 
                                         alt="头像">
                                    <span class="text-muted small" title="{{ $assignment->user->name }}">{{ Str::limit($assignment->user->name, 8) }}</span>
                                </div>
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

<style>
.hover-zoom:hover {
    transform: scale(1.01);
}
.bg-gradient {
    background: linear-gradient(135deg, #10b981, #059669);
}
.table thead th {
    vertical-align: middle;
}
</style>