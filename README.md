# Flex Learning System - E-Learning Portal

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Flex Learning System</strong> - A comprehensive e-learning platform built with Laravel
</p>

---

## Table of Contents / 目录

- [English Documentation](#english-documentation)
- [中文文档](#中文文档)
- [System Overview](#system-overview--系统概述)
- [Features](#features--功能特性)
- [Installation](#installation--安装指南)
- [Usage Guide](#usage-guide--使用指南)
- [Technical Documentation](#technical-documentation--技术文档)

---

## English Documentation

### System Overview

The **Flex Learning System** is a comprehensive e-learning portal designed to facilitate online education with multiple user roles, course management, assignment submission, and progress tracking. Built with Laravel framework, it provides a robust platform for educational institutions to manage their online learning programs.

### Key Features

- **Multi-Role User System**: Admin, Lecturer, and Student roles with different access levels
- **Course Management**: Create, edit, and manage courses with multimedia content
- **Assignment System**: Complete assignment creation, submission, and grading workflow
- **Progress Tracking**: Monitor student progress through topics and activities
- **File Management**: Upload and manage course materials, videos, documents, and slideshows
- **Responsive Design**: Mobile-friendly interface for all devices

### User Roles & Interfaces

#### 1. Admin Interface
- **Dashboard**: Overview of system statistics and quick access to management functions
- **User Management**: Register and manage students and lecturers
- **Course Management**: Create, edit, and organize courses
- **Assignment Management**: Create assignments and monitor submissions
- **System Monitoring**: Track assignment status and user activities

#### 2. Student Interface
- **Dashboard**: View enrolled courses and activities
- **Course Content**: Access course materials, videos, and documents
- **Assignment Submission**: Submit assignments with file uploads
- **Progress Tracking**: Monitor learning progress through topics
- **Profile Management**: Update personal information

#### 3. Lecturer Interface
- **Course Management**: Manage assigned courses and content
- **Student Monitoring**: Track student progress and submissions
- **Assignment Grading**: Grade and provide feedback on assignments

---

## 中文文档

### 系统概述

**Flex Learning System** 是一个全面的在线学习门户，旨在通过多用户角色、课程管理、作业提交和进度跟踪来促进在线教育。该系统基于 Laravel 框架构建，为教育机构提供了一个强大的在线学习项目管理平台。

### 主要功能

- **多角色用户系统**：管理员、讲师和学生角色，具有不同的访问级别
- **课程管理**：创建、编辑和管理包含多媒体内容的课程
- **作业系统**：完整的作业创建、提交和评分工作流程
- **进度跟踪**：监控学生通过主题和活动的学习进度
- **文件管理**：上传和管理课程材料、视频、文档和幻灯片
- **响应式设计**：适用于所有设备的移动友好界面

### 用户角色和界面

#### 1. 管理员界面
- **仪表板**：系统统计概览和快速访问管理功能
- **用户管理**：注册和管理学生和讲师
- **课程管理**：创建、编辑和组织课程
- **作业管理**：创建作业并监控提交情况
- **系统监控**：跟踪作业状态和用户活动

#### 2. 学生界面
- **仪表板**：查看注册的课程和活动
- **课程内容**：访问课程材料、视频和文档
- **作业提交**：通过文件上传提交作业
- **进度跟踪**：监控通过主题的学习进度
- **个人资料管理**：更新个人信息

#### 3. 讲师界面
- **课程管理**：管理分配的课程和内容
- **学生监控**：跟踪学生进度和提交情况
- **作业评分**：对作业进行评分和提供反馈

---

## Installation / 安装指南

### Prerequisites / 先决条件

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- SQLite (or MySQL/PostgreSQL)
- Web server (Apache/Nginx)

### Step-by-Step Installation / 逐步安装

1. **Clone the repository / 克隆仓库**
   ```bash
   git clone <repository-url>
   cd flex_learning_system1
   ```

2. **Install PHP dependencies / 安装 PHP 依赖**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies / 安装 Node.js 依赖**
   ```bash
   npm install
   ```

4. **Environment setup / 环境设置**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup / 数据库设置**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Storage setup / 存储设置**
   ```bash
   php artisan storage:link
   ```

7. **Start the development server / 启动开发服务器**
   ```bash
   php artisan serve
   ```

8. **Access the application / 访问应用程序**
   - Open your browser and go to `http://localhost:8000`

### Default Login Credentials / 默认登录凭据

- **Admin**: admin@gmail.com / 123
- **Student**: jane@gmail.com / jane123
- **Student**: peter@gmail.com / peter123

---

## Usage Guide / 使用指南

### For Administrators / 管理员使用指南

#### 1. Dashboard Overview / 仪表板概览
- Access the admin dashboard after logging in
- View system statistics and quick access cards
- Navigate through different management sections using the sidebar

#### 2. User Management / 用户管理
- **Register New Users**: Click "Register Account" to add students or lecturers
- **Manage Existing Users**: Use "Manage Users" to edit or delete user accounts
- **Assign Users to Courses**: Add users to specific courses with appropriate roles

#### 3. Course Management / 课程管理
- **Create Courses**: Add new courses with titles, descriptions, and cover images
- **Edit Courses**: Modify course information and settings
- **Course Activities**: Add CU Activities (Course Units) to organize content
- **Topics Management**: Create topics within activities with multimedia content

#### 4. Assignment Management / 作业管理
- **Create Assignments**: Set up assignments with due dates and descriptions
- **Monitor Submissions**: Check assignment status and student submissions
- **Grade Assignments**: Provide grades and feedback to students

### For Students / 学生使用指南

#### 1. Accessing the System / 访问系统
- Login with your student credentials
- View your enrolled courses on the dashboard
- Navigate through course activities and topics

#### 2. Course Content / 课程内容
- **View Activities**: Access different course units and their content
- **Download Materials**: Download course documents, slideshows, and resources
- **Watch Videos**: Stream course videos and tutorials
- **Track Progress**: Monitor your completion status for each topic

#### 3. Assignment Submission / 作业提交
- **View Assignments**: Check available assignments and their due dates
- **Submit Work**: Upload assignment files with comments
- **Check Grades**: View grades and feedback from instructors
- **Resubmit**: Update submissions if allowed

### For Lecturers / 讲师使用指南

#### 1. Course Management / 课程管理
- Access assigned courses through the admin interface
- Manage course content and activities
- Monitor student enrollment and progress

#### 2. Assignment Grading / 作业评分
- Review student submissions
- Provide grades and detailed feedback
- Track assignment completion rates

---

## Technical Documentation / 技术文档

### System Architecture / 系统架构

The Flex Learning System is built using the following technologies:

- **Backend**: Laravel 10.x (PHP Framework)
- **Frontend**: Blade Templates with Bootstrap 5
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **File Storage**: Laravel Storage with local filesystem
- **Authentication**: Laravel Sanctum

### Database Structure / 数据库结构

#### Core Tables / 核心表

1. **users** - User accounts with role-based access
2. **courses** - Course information and metadata
3. **enrollments** - User-course relationships with roles
4. **cu_activities** - Course unit activities
5. **topics** - Individual learning topics within activities
6. **assignments** - Assignment definitions and requirements
7. **assignment_submits** - Student assignment submissions
8. **topic_progress** - Student progress tracking

#### Key Relationships / 关键关系

- Users can have multiple enrollments in different courses
- Courses contain multiple CU Activities
- Activities contain multiple Topics
- Assignments are linked to specific activities
- Students submit assignments which are graded by instructors

### File Structure / 文件结构

```
flex_learning_system1/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Middleware/          # Custom middleware
├── database/
│   ├── migrations/          # Database schema definitions
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Web routes
└── public/
    ├── css/                # Stylesheets
    ├── js/                 # JavaScript files
    └── storage/            # File uploads
```

### API Endpoints / API 端点

The system uses traditional web routes with form submissions. Key routes include:

- **Authentication**: `/login`, `/register`, `/logout`
- **Admin Routes**: `/admin/*` (protected by admin middleware)
- **Student Routes**: `/student/*` (protected by student middleware)
- **File Operations**: Download and upload endpoints for course materials

### Security Features / 安全功能

- **Role-based Access Control**: Different access levels for admin, lecturer, and student
- **CSRF Protection**: All forms protected against CSRF attacks
- **File Upload Security**: Validated file types and sizes
- **Authentication Middleware**: Protected routes require proper authentication
- **Input Validation**: All user inputs are validated and sanitized

### Configuration / 配置

Key configuration files:

- **`.env`**: Environment variables and database configuration
- **`config/database.php`**: Database connection settings
- **`config/filesystems.php`**: File storage configuration
- **`config/auth.php`**: Authentication settings

### Troubleshooting / 故障排除

#### Common Issues / 常见问题

1. **File Upload Issues**: Check storage permissions and file size limits
2. **Database Connection**: Verify database configuration in `.env`
3. **Authentication Problems**: Clear cache and regenerate application key
4. **Storage Links**: Run `php artisan storage:link` if files are not accessible

#### Performance Optimization / 性能优化

- Enable query caching for frequently accessed data
- Optimize file storage for large multimedia content
- Use database indexing for better query performance
- Implement pagination for large data sets

---

## Contributing / 贡献

We welcome contributions to improve the Flex Learning System. Please follow these guidelines:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License / 许可证

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Support / 支持

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation for common solutions

---

*Last updated: January 2025*
*最后更新：2025年1月*
