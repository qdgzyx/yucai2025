<!-- 新增悬浮菜单栏 -->
<button class="toggle-sidebar-btn">&#9776;</button>

<div class="sidebar-menu">
    <ul>
        <li><a href="{{ route('reports.index') }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">📊 数据汇总</a></li>
        <li><a href="{{ route('quantify.display') }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">📈 量化公示</a></li>
        <li class="dropdown">
            <a href="#" class="btn btn-secondary dropdown-toggle w-100 mb-2 rounded-pill shadow-sm" data-bs-toggle="dropdown">👨🎓 学生出勤</a>
            <ul class="dropdown-menu border-0 shadow-lg" style="min-width: 100%">
                <li><a class="dropdown-item py-2" href="{{ route('reports.summary.grade', 1) }}">七年级</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('reports.summary.grade', 2) }}">八年级</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('reports.summary.grade', 3) }}">九年级</a></li>
            </ul>
        </li>
        <li><a href="{{ route('quantify_records.create') }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">✏️ 添加记录</a></li>
        <li><a href="{{ route('categories.show', 4) }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">📢 通知公告</a></li>
        <li><a href="{{ route('categories.show', 2) }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">📜 规章制度</a></li>
        <li><a href="{{ route('reports.create') }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">📅 每日上报</a></li>
        <li><a href="{{ route('banjis.assignments', ['banji' => Auth::user()->banji_id ?? 1]) }}" class="btn btn-secondary w-100 mb-2 rounded-pill shadow-sm">📚 作业清单</a></li>
    </ul>
</div>

<script> 
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('.toggle-sidebar-btn');
        const sidebar = document.querySelector('.sidebar-menu');

        if (toggleBtn && sidebar) {  // 确保元素存在
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('open');
                
                // Add animation effect
                if (sidebar.classList.contains('open')) {
                    sidebar.style.transition = 'left 0.3s ease';
                    sidebar.style.left = '0';
                } else {
                    sidebar.style.transition = 'left 0.3s ease';
                    sidebar.style.left = '-200px'; // 修改为-200px以匹配新的宽度
                }
            });
        } else {
            console.error('Toggle button or sidebar menu not found.');
        }
    });
</script>
