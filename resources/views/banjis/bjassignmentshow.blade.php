

@section('content')
<div class="card border-0 shadow-sm" style="max-width: 1200px; margin: 2rem auto; background: #f8fafc;">
    <div class="card-header bg-white border-bottom-0 py-4">
        <div class="d-flex justify-content-between align-items-center">
            <!-- 标题部分 -->
            <div class="text-center flex-grow-1">
                <h3 class="mb-0 text-dark" style="font-weight: 600; letter-spacing: 1px;">
                    <i class="bi bi-journal-bookmark me-2" style="color: #6c757d;"></i>
                    {{ $banji->name }}作业清单 
                </h3>
               
            </div>

            <!-- 日期选择表单 -->
            <form method="GET" action="{{ route('banjis.show', $banji) }}" class="bg-transparent p-0" style="width: 280px;">
                <div class="input-group">
                    <input type="date" name="date" 
                           value="{{ $date }}"
                           class="form-control border-secondary shadow-none" 
                           style="border-radius: 20px 0 0 20px;">
                    <button type="submit" 
                            class="btn btn-outline-secondary shadow-none" 
                            style="border-radius: 0 20px 20px 0; padding: 0.375rem 1.2rem;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <!-- 表格标题行 -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-striped">
                <thead class="bg-light-gray">
                    <tr>
                        <th style="width:8%">学科</th>
                        <th style="width:60%">作业内容</th>
                        <th class="text-center" style="width:8%">附件</th>
                        <th class="text-center" style="width:8%">布置时间</th>
                        <th class="text-center" style="width:8%">截止时间</th>
                        <th class="text-center" style="width:8%">教师</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedAssignments as $subject => $assignments)
                        @foreach($assignments as $assignment)
                        <tr class="{{ $loop->even ? 'bg-soft-gray' : '' }}">
                            <td class="align-middle">
                                <span class="fw-bold text-dark">{{ $subject }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">  
                                    <span class="text-dark" style="font-size: 1.1rem;">{!!$assignment->content!!}</span>
                                    
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                @if($assignment->attachment)
                                <a href="{{ asset('storage/'.$assignment->attachment) }}" 
                                   class="btn btn-sm btn-outline-secondary px-3"
                                   data-bs-toggle="tooltip" 
                                   title="点击下载">
                                    <i class="bi bi-paperclip"></i>
                                </a>
                                @else
                                <span class="badge bg-light text-secondary border">无附件</span>
                                @endif
                            </td>
                            <td class="text-center align-middle text-nowrap">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $assignment->publish_at->format('m/d H:i') }}
                            </td>
                            <td class="text-center align-middle text-nowrap">
                                <span class="badge {{ $assignment->deadline->isPast() ? 'bg-soft-red text-danger' : 'bg-soft-green text-success' }}">
                                    {{ $assignment->deadline->format('m/d H:i') }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="text-muted">{{ $assignment->user->name }}</span>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-file-text display-6 text-muted mb-3"></i>
                            <p class="text-muted mb-0">该日期暂无作业任务</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-light-gray {
        background-color: #f1f3f5;
    }
    .bg-soft-gray {
        background-color: #f8f9fa;
    }
    .bg-soft-red {
        background-color: #ffe3e3;
        border: 1px solid #ffc9c9;
    }
    .bg-soft-green {
        background-color: #d3f9d8;
        border: 1px solid #b2f2bb;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffff;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc !important;
        transform: translateX(2px);
        transition: all 0.2s ease;
    }
</style>
@endsection