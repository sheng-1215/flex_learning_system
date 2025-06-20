<div class="sidebar">
    <h4 class="text-center mb-4"><span class="text-warning">Flex</span> Learning</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item"><a href="{{ route('admin_dashboard') }}" class="nav-link{{ request()->routeIs('admin_dashboard') ? ' active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
        <li><a href="{{ route('admin.registerStudentView') }}" class="nav-link{{ request()->routeIs('admin.registerStudentView') ? ' active' : '' }}"><i class="fas fa-user-plus mr-2"></i>Register Student</a></li>
        <li><a href="{{ route('admin.users') }}" class="nav-link{{ request()->routeIs('admin.users') ? ' active' : '' }}"><i class="fas fa-users-cog mr-2"></i>Manage Users</a></li>
        <li><a href="{{ route('admin.courses') }}" class="nav-link{{ request()->routeIs('admin.courses') ? ' active' : '' }}"><i class="fas fa-book mr-2"></i>Add Course</a></li>
        <li><a href="{{ route('admin.selectCourseForAssignment') }}" class="nav-link{{ request()->routeIs('admin.selectCourseForAssignment') ? ' active' : '' }}"><i class="fas fa-tasks mr-2"></i>Add Assignment</a></li>
        <li><a href="#" class="nav-link"><i class="fas fa-clipboard-check mr-2"></i>Check Assignment Status</a></li>
        <li><a href="#" class="nav-link"><i class="fas fa-pen mr-2"></i>Grade Assignment</a></li>
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