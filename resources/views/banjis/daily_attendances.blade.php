{{-- 修改表格样式与视觉层次 --}}
<div class="card border-0 shadow-sm" style="margin: 2rem auto; border-radius: 1.5rem;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient text-white" style="background: linear-gradient(135deg, #4f46e5, #3b82f6);">
                    <tr>
                        {{-- 添加图标与文字间距 --}}
                        <th class="py-3"><i class="fas fa-users me-2"></i>应到人数</th>
                        <th class="py-3"><i class="fas fa-check-circle me-2"></i>实到人数</th>
                        <th class="py-3"><i class="fas fa-bed me-2"></i>病假</th>
                        <th class="py-3"><i class="fas fa-list-ul me-2"></i>名单</th>
                        <th class="py-3"><i class="fas fa-user-clock me-2"></i>事假</th>
                        <th class="py-3"><i class="fas fa-list-ul me-2"></i>名单</th>
                        <th class="py-3"><i class="fas fa-exclamation-triangle me-2"></i>缺勤</th>
                        <th class="py-3"><i class="fas fa-flag me-2"></i>状态</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        {{-- 增加行悬停动画与阴影 --}}
                        <tr class="hover-row" style="transition: all 0.3s ease; cursor: pointer;">
                            <td class="fw-bold fs-5">{{ $report->total_expected }}</td>
                            <td class="fw-bold fs-5">{{ $report->total_actual }}</td>
                            <td><span class="badge bg-info-subtle text-info">{{ $report->sick_leave_count }}</span></td>
                            {{-- 修改：添加换行显示名单 --}}
                            <td title="{{ $report->sick_list }}">
                                <div style="max-height: 3em; overflow: hidden;">
                                    {{ Str::limit(str_replace(',', "\n", $report->sick_list), 30) }}
                                </div>
                            </td>
                            <td><span class="badge bg-warning-subtle text-warning">{{ $report->personal_leave_count }}</span></td>
                            {{-- 修改：添加换行显示名单 --}}
                            <td title="{{ $report->personal_list }}">
                                <div style="max-height: 3em; overflow: hidden;">
                                    {{ Str::limit(str_replace(',', "\n", $report->personal_list), 30) }}
                                </div>
                            </td>
                            <td><span class="badge bg-danger-subtle text-danger">{{ $report->absent_count }}</span></td>
                            <td>
                                {{-- 状态标签优化 --}}
                                @switch($report->report_status)
                                    @case('complete')
                                        <span class="badge bg-success-subtle text-success d-flex align-items-center gap-1">
                                            <i class="fas fa-check-circle"></i> 完整
                                        </span>
                                        @break
                                    @case('incomplete')
                                        <span class="badge bg-warning-subtle text-warning d-flex align-items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> 不完整
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary-subtle text-secondary d-flex align-items-center gap-1">
                                            <i class="fas fa-question-circle"></i> 未知
                                        </span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        {{-- 统一空状态样式 --}}
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">暂无出勤数据</h5>
                                <p class="small text-muted">系统将在更新后自动展示最新记录</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- 分页样式优化 --}}
            @if($reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3 d-flex justify-content-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm">
                            {!! $reports->render() !!}
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-row:hover {
    background-color: #f0f5ff !important;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.bg-gradient {
    background: linear-gradient(135deg, #4f46e5, #3b82f6);
}
.badge {
    font-weight: 500;
}
</style>