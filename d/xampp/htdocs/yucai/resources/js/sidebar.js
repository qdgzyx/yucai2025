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