<!-- 作业公示 -->
<div class="col-md-12 mb-4">
    <div class="card h-100">
        <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #5d4037, #3e2723);">
            <h4 class="card-title m-0"><i class="fas fa-book mr-2"></i> 作业公示</h4>
        </div>
        <div class="card-body p-0">
            @include('banjis.daily_assignments', [
                'groupedAssignments' => $assignments, // 确保传递正确的变量名
                'date' => $date
            ])
        </div>
    </div>
</div>