<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Status Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .card { border-radius: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, .08); }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: #ffc107;
            color: #343a40;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        @media (min-width: 992px) {
            .menu-toggle { display: none; }
        }
        /* Additional styles from original dashboard */
        .card {
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .border-left-primary {
            border-left: 0.25rem solid #007bff;
        }
        .border-left-success {
            border-left: 0.25rem solid #28a745;
        }
        .border-left-info {
            border-left: 0.25rem solid #17a2b8;
        }
        .border-left-warning {
            border-left: 0.25rem solid #ffc107;
        }
        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }
        .progress-bar {
            border-radius: 10px;
        }
        .badge-pill {
            padding: 0.5em 1em;
            font-size: 0.85em;
        }
        @media (max-width: 767.98px) {
            .col-xl-6, .col-lg-6 {
                max-width: 100%;
                flex: 0 0 100%;
            }
            .card-header h5 {
                font-size: 1rem;
            }
            .btn-group {
                flex-direction: column;
                width: 100%;
            }
            .btn-group .btn {
                margin-bottom: 0.25rem;
                border-radius: 0.25rem !important;
            }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <div class="content">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-dark">
                                <i class="fas fa-chart-line text-primary mr-2"></i>
                                Assignment Status Dashboard
                            </h2>
                            <p class="text-muted mb-0">Monitor all CU Activities and their assignment submission status</p>
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('admin_dashboard') }}" class="btn btn-outline-secondary mr-2">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Activities
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activities->count() ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Assignments
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $activities->sum('stats.total_assignments') ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tasks fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Submissions
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $activities->sum('stats.total_submissions') ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-upload fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Submissions
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $activities->sum('stats.not_submitted_count') ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activities List -->
            <div class="row">
                @forelse($activities ?? [] as $activity)
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-book-open mr-2"></i>
                                    {{ $activity->title ?? 'No Title' }}
                                </h5>
                                <span class="badge badge-light text-primary">
                                    {{ $activity->course->title ?? 'No Course' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <p class="card-text text-muted mb-3">
                                {{ Str::limit($activity->description ?? 'No description available', 100) }}
                            </p>
                            
                            <!-- Assignment Statistics -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-center p-2 bg-light rounded">
                                        <div class="h4 mb-0 text-primary">{{ $activity->stats['total_assignments'] ?? 0 }}</div>
                                        <small class="text-muted">Assignments</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-2 bg-light rounded">
                                        <div class="h4 mb-0 text-info">{{ $activity->stats['enrolled_students'] ?? 0 }}</div>
                                        <small class="text-muted">Enrolled Students</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submission Progress -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Submission Progress</small>
                                    <small class="text-muted">
                                        {{ $activity->stats['submitted_count'] ?? 0 }}/{{ $activity->stats['total_expected_submissions'] ?? 0 }}
                                        ({{ $activity->stats['progress_percentage'] ?? 0 }}%)
                                    </small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $activity->stats['progress_percentage'] ?? 0 }}%" 
                                         aria-valuenow="{{ $activity->stats['progress_percentage'] ?? 0 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Badges -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="text-center">
                                        <span class="badge badge-success badge-pill">
                                            {{ $activity->stats['submitted_count'] ?? 0 }}
                                        </span>
                                        <small class="d-block text-muted">Submitted</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <span class="badge badge-warning badge-pill">
                                            {{ $activity->stats['not_submitted_count'] ?? 0 }}
                                        </span>
                                        <small class="d-block text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <span class="badge badge-info badge-pill">
                                            {{ $activity->stats['total_submissions'] ?? 0 }}
                                        </span>
                                        <small class="d-block text-muted">Total Files</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Due: {{ $activity->due_date ? \Carbon\Carbon::parse($activity->due_date)->format('M d, Y') : 'No due date' }}
                                </small>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.activityAssignment.view', $activity->id) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-chart-bar mr-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No CU Activities Found</h5>
                            <p class="text-muted">There are currently no CU Activities in the system.</p>
                            <a href="{{ route('admin.courses') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> Create Course Activity
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
        // 实时更新统计数据
        function updateStats() {
            fetch('{{ route("admin.assignmentStats") }}')
                .then(response => response.json())
                .then(data => {
                    // 更新统计卡片
                    const totalActivitiesEl = document.querySelector('.border-left-primary .h5');
                    const totalAssignmentsEl = document.querySelector('.border-left-success .h5');
                    const totalSubmissionsEl = document.querySelector('.border-left-info .h5');
                    const pendingEl = document.querySelector('.border-left-warning .h5');
                    
                    if (totalActivitiesEl) totalActivitiesEl.textContent = data.total_activities || 0;
                    if (totalAssignmentsEl) totalAssignmentsEl.textContent = data.total_assignments || 0;
                    if (totalSubmissionsEl) totalSubmissionsEl.textContent = data.total_submissions || 0;
                    
                    // 计算待提交数量
                    const totalExpected = (data.total_assignments || 0) * (data.total_students || 0);
                    const pendingCount = Math.max(0, totalExpected - (data.total_submissions || 0));
                    if (pendingEl) pendingEl.textContent = pendingCount;
                })
                .catch(error => console.error('Error updating stats:', error));
        }

        // 页面加载完成后每30秒更新一次统计数据
        document.addEventListener('DOMContentLoaded', function() {
            // 初始更新
            updateStats();
            
            // 设置定时更新
            setInterval(updateStats, 30000);
        });
    </script>
</body>
</html>