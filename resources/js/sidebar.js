document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.toggle-sidebar-btn');
    const sidebar = document.querySelector('.sidebar-menu');

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });
});
// 修改动画方向为水平移动
if (sidebar.classList.contains('open')) {
    sidebar.style.left = '0';
} else {
    sidebar.style.left = '-250px';
}