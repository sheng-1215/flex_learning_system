<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Lecturer Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: #fff;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 60px;
            transition: left 0.3s, width 0.3s;
            z-index: 1030;
        }
        .sidebar .nav-link {
            color: #fff;
            font-size: 1rem;
            padding: 12px 20px;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #495057;
            color: #ffc107;
        }
        .sidebar-toggler {
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 1040;
            background: #343a40;
            color: #fff;
            border: none;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            display: none;
        }
        .content {
            margin-left: 200px;
            padding: 40px 20px 20px 20px;
            transition: margin-left 0.3s, width 0.3s;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: box-shadow 0.2s;
            margin-bottom: 20px;
        }
        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        .welcome {
            margin-bottom: 30px;
            padding-top: 10px;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                left: 0;
                width: 50vw;
            }
            .sidebar:not(.show) {
                left: -50vw;
            }
            .sidebar-toggler {
                display: block;
            }
            .content {
                margin-left: 0;
                width: 100vw;
                padding-top: 60px;
            }
            .sidebar.show ~ .content {
                width: 50vw;
                margin-left: 50vw;
            }
        }
        @media (max-width: 767.98px) {
            .dashboard-cards .col-md-6, .dashboard-cards .col-lg-4 {
                max-width: 100%;
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 class="text-center mb-4"><span class="text-warning">Flex</span> Learning</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="{{ route('admin_dashboard') }}" class="nav-link active"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
            <li><a href="{{ route('admin.registerStudentView') }}" class="nav-link"><i class="fas fa-user-plus mr-2"></i>Register Student</a></li>
            <li><a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users-cog mr-2"></i>Manage Users</a></li>
            <li><a href="{{ route('admin.courses') }}" class="nav-link"><i class="fas fa-book mr-2"></i>Manage Courses</a></li>
            <li><a href="{{ route('admin.selectCourseForAssignment') }}" class="nav-link"><i class="fas fa-tasks mr-2"></i>Add Assignment</a></li>
            <li><a href="#" class="nav-link"><i class="fas fa-clipboard-check mr-2"></i>Check Assignment Status</a></li>
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
    <!-- Content -->
    <div class="content" id="mainContent">
        <div class="welcome">
            <h2>Welcome to the Control Panel - {{ Auth::user()->name }}</h2>
            <p class="text-muted">Manage courses, students, assignments and more from this dashboard.</p>
        </div>
        <div class="row dashboard-cards">
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.registerStudentView') }}" class="text-decoration-none">
                    <div class="card p-3 text-center">
                        <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                        <h6>Register Account</h6>
                        <p class="text-muted small">Add new students and lecturers to the system.</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.courses') }}" class="text-decoration-none">
                    <div class="card p-3 text-center">
                        <i class="fas fa-book fa-2x text-success mb-2"></i>
                        <h6>Manage Courses</h6>
                        <p class="text-muted small">Create and manage courses.</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.selectCourseForAssignment') }}" class="text-decoration-none">
                    <div class="card p-3 text-center">
                        <i class="fas fa-tasks fa-2x text-warning mb-2"></i>
                        <h6>Add Assignment</h6>
                        <p class="text-muted small">Create assignments for students.</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card p-3 text-center">
                        <i class="fas fa-users-cog fa-2x text-info mb-2"></i>
                        <h6>Manage Users</h6>
                        <p class="text-muted small">Edit student and lecturer details.</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="#" class="text-decoration-none">
                    <div class="card p-3 text-center">
                        <i class="fas fa-clipboard-check fa-2x text-secondary mb-2"></i>
                        <h6>Check Assignment Status</h6>
                        <p class="text-muted small">Monitor student assignment submissions.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS & Sidebar Toggle Script -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('mainContent');
        function updateSidebarLayout() {
            if(window.innerWidth < 992) {
                if(sidebar.classList.contains('show')) {
                    mainContent.style.width = '50vw';
                    mainContent.style.marginLeft = '50vw';
                } else {
                    mainContent.style.width = '100vw';
                    mainContent.style.marginLeft = '0';
                }
            } else {
                mainContent.style.width = '';
                mainContent.style.marginLeft = '';
            }
        }
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            updateSidebarLayout();
        });
        // 点击内容区时自动收起侧边栏（移动端）
        mainContent.addEventListener('click', function() {
            if(window.innerWidth < 992 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                updateSidebarLayout();
            }
        });
        window.addEventListener('resize', updateSidebarLayout);
        // 初始化
        updateSidebarLayout();
    </script>
</body>
</html> 