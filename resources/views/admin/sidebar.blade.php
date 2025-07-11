<div class="sidebar">
    <h4 class="text-center mb-4"><span class="text-warning">Flex</span> Learning</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item"><a href="{{ route('admin_dashboard') }}" class="nav-link{{ request()->routeIs('admin_dashboard') ? ' active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
        <li><a href="{{ route('admin.registerStudentView') }}" class="nav-link{{ request()->routeIs('admin.registerStudentView') ? ' active' : '' }}"><i class="fas fa-user-plus mr-2"></i>Register Account</a></li>
        <li><a href="{{ route('admin.users') }}" class="nav-link{{ request()->routeIs('admin.users') ? ' active' : '' }}"><i class="fas fa-users-cog mr-2"></i>Manage Users</a></li>
        <li><a href="{{ route('admin.courses') }}" class="nav-link{{ request()->routeIs('admin.courses') ? ' active' : '' }}"><i class="fas fa-book mr-2"></i>Manage Courses</a></li>
        <li><a href="{{ route('admin.selectCourseForAssignment') }}" class="nav-link{{ request()->routeIs('admin.selectCourseForAssignment') ? ' active' : '' }}"><i class="fas fa-tasks mr-2"></i>Add Assignment</a></li>
        
        {{-- <li><a href="{{ route('admin.checkAssignments') }}" class="nav-link"><i class="fas fa-clipboard-check mr-2"></i>Check Assignment Status</a></li> --}}
        {{-- <li><a href="#" class="nav-link"><i class="fas fa-pen mr-2"></i>Grade Assignment</a></li> --}}
        <li>
            <form id="logout-form" action="{{ route('logoutFunction') }}" method="POST" style="display:inline;">
                @csrf
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </form>
        </li>
    </ul>
</div>
<style>
    .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
    @media (max-width: 991.98px) {
        .sidebar { left: -200px; }
        .sidebar.active { left: 0; }
    }
</style>
<!-- 移动端Menu按钮 -->
<button id="menuToggleBtn" class="btn btn-warning d-lg-none"
        onclick="toggleSidebar()"
        style="position:fixed;top:16px;left:16px;z-index:1100;border-radius:50%;width:48px;height:48px;box-shadow:0 2px 8px rgba(0,0,0,0.15);padding:0;display:flex;align-items:center;justify-content:center;">
    <span style="font-size:1.5rem;">☰</span>
</button>
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');
        document.getElementById('menuToggleBtn').style.display = sidebar.classList.contains('active') ? 'none' : 'flex';
    }
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        document.getElementById('menuToggleBtn').style.display = sidebar.classList.contains('active') ? 'none' : 'flex';

        // 新增：点击内容区自动收起sidebar
        document.addEventListener('click', function(e) {
            // 只在小屏幕且sidebar已展开时生效
            if (window.innerWidth <= 991.98 && sidebar.classList.contains('active')) {
                // 如果点击的不是sidebar本身，也不是sidebar的子元素，也不是Menu按钮
                if (!sidebar.contains(e.target) && e.target.id !== 'menuToggleBtn') {
                    sidebar.classList.remove('active');
                    document.getElementById('menuToggleBtn').style.display = 'flex';
                }
            }
        });
    });
</script> 