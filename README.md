# Flex Learning System - E-Learning Portal

## 系统概述 / System Overview

**English:**
This is a comprehensive e-learning portal system built with Laravel 10, designed to facilitate online education with course management, assignment submission, and student progress tracking. The system supports multiple user roles (Admin, Lecturer, Student) and provides a complete learning management experience.

**中文:**
这是一个使用 Laravel 10 构建的综合电子学习门户系统，旨在促进在线教育，包括课程管理、作业提交和学生进度跟踪。系统支持多种用户角色（管理员、讲师、学生），提供完整的学习管理体验。

## 技术栈 / Technology Stack

**English:**
- **Backend:** Laravel 10 (PHP 8.1+)
- **Database:** SQLite (Primary), MySQL support
- **Frontend:** Blade Templates, Bootstrap 4, CSS3, JavaScript
- **Authentication:** Laravel Sanctum
- **File Storage:** Laravel Storage (Public disk)

**中文:**
- **后端:** Laravel 10 (PHP 8.1+)
- **数据库:** SQLite (主数据库), MySQL 支持
- **前端:** Blade 模板, Bootstrap 4, CSS3, JavaScript
- **身份验证:** Laravel Sanctum
- **文件存储:** Laravel Storage (公共磁盘)

## 系统功能 / System Features

### 用户角色 / User Roles

**English:**
1. **Admin (管理员):** Full system control, user management, course creation
2. **Lecturer (讲师):** Course management, assignment creation, grading
3. **Student (学生):** Course access, assignment submission, progress tracking

**中文:**
1. **管理员:** 完整系统控制、用户管理、课程创建
2. **讲师:** 课程管理、作业创建、评分
3. **学生:** 课程访问、作业提交、进度跟踪

### 核心功能 / Core Features

**English:**
- **Course Management:** Create, edit, and manage courses with cover images
- **User Management:** Register and manage students, lecturers, and admins
- **Assignment System:** Create assignments, track submissions, and provide grades
- **Content Delivery:** Support for videos, documents, and slideshows
- **Progress Tracking:** Monitor student progress through topics and assignments
- **File Management:** Upload and organize course materials and assignments

**中文:**
- **课程管理:** 创建、编辑和管理带封面图片的课程
- **用户管理:** 注册和管理学生、讲师和管理员
- **作业系统:** 创建作业、跟踪提交并提供评分
- **内容交付:** 支持视频、文档和幻灯片
- **进度跟踪:** 监控学生通过主题和作业的进度
- **文件管理:** 上传和组织课程材料和作业

## 安装指南 / Installation Guide

### 系统要求 / System Requirements

**English:**
- PHP 8.1 or higher
- Composer
- SQLite or MySQL
- Web server (Apache/Nginx)

**中文:**
- PHP 8.1 或更高版本
- Composer
- SQLite 或 MySQL
- Web 服务器 (Apache/Nginx)

### 安装步骤 / Installation Steps

**English:**

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd flex_learning_system1
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup:**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   touch database/second_db.sqlite
   
   # Run migrations
   php artisan migrate
   ```

5. **Storage setup:**
   ```bash
   php artisan storage:link
   ```

6. **Start the server:**
   ```bash
   php artisan serve
   ```

**中文:**

1. **克隆仓库:**
   ```bash
   git clone <repository-url>
   cd flex_learning_system1
   ```

2. **安装依赖:**
   ```bash
   composer install
   ```

3. **环境设置:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **数据库设置:**
   ```bash
   # 对于 SQLite (默认)
   touch database/database.sqlite
   touch database/second_db.sqlite
   
   # 运行迁移
   php artisan migrate
   ```

5. **存储设置:**
   ```bash
   php artisan storage:link
   ```

6. **启动服务器:**
   ```bash
   php artisan serve
   ```

## 系统架构 / System Architecture

### 数据库结构 / Database Structure

**English:**
The system uses two SQLite databases:
- **Primary Database (`database.sqlite`):** Main application data
- **Secondary Database (`second_db.sqlite`):** Student portal integration

**Key Tables:**
- `users` - User accounts and authentication
- `courses` - Course information and metadata
- `enrollments` - User-course relationships
- `cu_activities` - Course activities and assignments
- `topics` - Learning content (videos, documents, slideshows)
- `assignments` - Assignment definitions
- `assignment_submits` - Student submissions
- `topic_progress` - Student progress tracking

**中文:**
系统使用两个 SQLite 数据库：
- **主数据库 (`database.sqlite`):** 主要应用程序数据
- **辅助数据库 (`second_db.sqlite`):** 学生门户集成

**关键表:**
- `users` - 用户账户和身份验证
- `courses` - 课程信息和元数据
- `enrollments` - 用户-课程关系
- `cu_activities` - 课程活动和作业
- `topics` - 学习内容（视频、文档、幻灯片）
- `assignments` - 作业定义
- `assignment_submits` - 学生提交
- `topic_progress` - 学生进度跟踪

## 使用指南 / User Guide

### 管理员操作 / Admin Operations

**English:**

#### 1. User Management
- Navigate to "Manage Users" from the admin dashboard
- Register new students and lecturers
- Edit existing user information
- Assign users to courses

#### 2. Course Management
- Create new courses with cover images
- Set course start and end dates
- Assign lecturers to courses
- Enroll students in courses

#### 3. Content Creation
- Add CU Activities to courses
- Create topics (videos, documents, slideshows)
- Upload course materials
- Set due dates for activities

#### 4. Assignment Management
- Create assignments for activities
- Upload assignment files
- Set due dates
- Grade student submissions
- Provide feedback

**中文:**

#### 1. 用户管理
- 从管理员仪表板导航到"管理用户"
- 注册新学生和讲师
- 编辑现有用户信息
- 将用户分配到课程

#### 2. 课程管理
- 创建带封面图片的新课程
- 设置课程开始和结束日期
- 将讲师分配到课程
- 将学生注册到课程

#### 3. 内容创建
- 向课程添加 CU 活动
- 创建主题（视频、文档、幻灯片）
- 上传课程材料
- 为活动设置截止日期

#### 4. 作业管理
- 为活动创建作业
- 上传作业文件
- 设置截止日期
- 为学生提交评分
- 提供反馈

### 学生操作 / Student Operations

**English:**

#### 1. Course Access
- Login with student credentials
- View enrolled courses
- Access course activities
- Track learning progress

#### 2. Content Learning
- Watch videos and view documents
- Download course materials
- Mark topics as completed
- View progress indicators

#### 3. Assignment Submission
- View assigned tasks
- Download assignment files
- Submit completed work
- View grades and feedback
- Delete and resubmit if needed

**中文:**

#### 1. 课程访问
- 使用学生凭据登录
- 查看注册的课程
- 访问课程活动
- 跟踪学习进度

#### 2. 内容学习
- 观看视频和查看文档
- 下载课程材料
- 将主题标记为已完成
- 查看进度指示器

#### 3. 作业提交
- 查看分配的任务
- 下载作业文件
- 提交完成的工作
- 查看成绩和反馈
- 如需要可删除并重新提交

## 路由说明 / Route Documentation

### 主要路由 / Main Routes

**English:**

#### Authentication Routes
- `GET /` - Login page
- `POST /login` - Login function
- `GET /register` - Registration page
- `POST /register` - Registration function
- `POST /logout` - Logout function

#### Admin Routes (Protected)
- `GET /admin_dashboard` - Admin dashboard
- `GET /admin/courses` - Course management
- `GET /admin/users` - User management
- `GET /admin/assignments/select-course` - Assignment creation

#### Student Routes (Protected)
- `GET /student/dashboard` - Student dashboard
- `GET /student/CUActivity/{activity}` - Activity details
- `GET /student/assignment` - Assignment list
- `POST /student/assignmentSubmit/{id}` - Submit assignment

**中文:**

#### 身份验证路由
- `GET /` - 登录页面
- `POST /login` - 登录功能
- `GET /register` - 注册页面
- `POST /register` - 注册功能
- `POST /logout` - 登出功能

#### 管理员路由（受保护）
- `GET /admin_dashboard` - 管理员仪表板
- `GET /admin/courses` - 课程管理
- `GET /admin/users` - 用户管理
- `GET /admin/assignments/select-course` - 作业创建

#### 学生路由（受保护）
- `GET /student/dashboard` - 学生仪表板
- `GET /student/CUActivity/{activity}` - 活动详情
- `GET /student/assignment` - 作业列表
- `POST /student/assignmentSubmit/{id}` - 提交作业

## 配置说明 / Configuration

### 环境变量 / Environment Variables

**English:**
Key environment variables in `.env`:

```env
APP_NAME="Flex Learning System"
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# File storage
FILESYSTEM_DISK=public
```

**中文:**
`.env` 中的关键环境变量：

```env
APP_NAME="Flex Learning System"
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# 文件存储
FILESYSTEM_DISK=public
```

### 文件存储 / File Storage

**English:**
- Course cover images: `storage/app/public/covers/`
- Assignment files: `storage/app/public/assignments/`
- Topic files: `storage/app/public/topic_files/`
- Student submissions: `storage/app/public/assignments/`

**中文:**
- 课程封面图片: `storage/app/public/covers/`
- 作业文件: `storage/app/public/assignments/`
- 主题文件: `storage/app/public/topic_files/`
- 学生提交: `storage/app/public/assignments/`

## 故障排除 / Troubleshooting

### 常见问题 / Common Issues

**English:**

#### 1. Database Connection Issues
```bash
# Check if SQLite files exist
ls -la database/
# Create if missing
touch database/database.sqlite
touch database/second_db.sqlite
```

#### 2. Storage Link Issues
```bash
# Recreate storage link
php artisan storage:link
```

#### 3. Permission Issues
```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

**中文:**

#### 1. 数据库连接问题
```bash
# 检查 SQLite 文件是否存在
ls -la database/
# 如果缺失则创建
touch database/database.sqlite
touch database/second_db.sqlite
```

#### 2. 存储链接问题
```bash
# 重新创建存储链接
php artisan storage:link
```

#### 3. 权限问题
```bash
# 设置适当的权限
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 许可证 / License

**English:**
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

**中文:**
此项目是在 [MIT 许可证](https://opensource.org/licenses/MIT) 下许可的开源软件。

## 支持 / Support

**English:**
For support and questions, please contact the development team or create an issue in the repository.

**中文:**
如需支持和问题，请联系开发团队或在仓库中创建问题。

---

**English:** This README provides a comprehensive guide to understanding, installing, and using the Flex Learning System. The system is designed to be user-friendly while providing powerful features for educational institutions.

**中文:** 此 README 提供了理解、安装和使用 Flex Learning System 的综合指南。该系统旨在用户友好，同时为教育机构提供强大的功能。
