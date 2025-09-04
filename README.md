Installation Guide / 安装指南
English:
Follow these steps to set up the system on your computer. This is for local testing; for a real server, consult a web host.

Download and Install XAMPP:

Go to https://www.apachefriends.org/index.html.
Download XAMPP for your OS (e.g., Windows).
Install it (click "Next" through the wizard).
Start XAMPP and run Apache and MySQL modules.


Set Up the Database:

Open phpMyAdmin (in XAMPP: http://localhost/phpmyadmin).
Create a new database named "elearning" (or import from the thesis SQL files if available).
If using SQLite, use DB Browser for SQLite (download from https://sqlitebrowser.org/) to create/open the database file.


Copy the Project Files:

Extract the project files (from the thesis attachment or source code) to XAMPP's "htdocs" folder (e.g., C:\xampp\htdocs\elearning).
Edit .env file (if using Laravel) to set database details: DB_CONNECTION=sqlite or mysql, DB_DATABASE=elearning.


Install Dependencies:

Open Command Prompt/Terminal in the project folder.
Run: composer install (install Composer first from https://getcomposer.org/ if needed).
Run: php artisan migrate to set up tables.
Run: php artisan serve to start the local server.


Access the System:

Open your browser and go to http://localhost:8000 (or the port shown).
You're ready! The login page should appear.



中文:
按照以下步骤在电脑上设置系统。这是本地测试；用于真实服务器，请咨询网络主机。

下载并安装XAMPP:

访问 https://www.apachefriends.org/index.html。
下载适合你的操作系统的XAMPP（例如Windows）。
安装它（点击“Next”完成向导）。
启动XAMPP并运行Apache和MySQL模块。


设置数据库:

打开phpMyAdmin（在XAMPP中：http://localhost/phpmyadmin）。
创建一个名为“elearning”的新数据库（或从论文SQL文件导入，如果可用）。
如果使用SQLite，从 https://sqlitebrowser.org/ 下载DB Browser for SQLite来创建/打开数据库文件。


复制项目文件:

将项目文件（从论文附件或源代码）解压到XAMPP的“htdocs”文件夹（例如C:\xampp\htdocs\elearning）。
编辑.env文件（如果使用Laravel）设置数据库细节：DB_CONNECTION=sqlite 或 mysql, DB_DATABASE=elearning。


安装依赖:

在项目文件夹中打开命令提示符/终端。
运行：composer install（如果需要，先从 https://getcomposer.org/ 安装Composer）。
运行：php artisan migrate 设置表格。
运行：php artisan serve 启动本地服务器。


访问系统:

打开浏览器访问 http://localhost:8000（或显示的端口）。
准备好了！登录页面应该出现。



Usage Guide / 使用指南
English:
The system has modules for Admin/Lecturer and Student. We'll explain step by step, assuming you're logged in. Default admin credentials: Email - admin@example.com, Password - password (change after first login).
1. Logging In (All Users) / 登录（所有用户）

Open the browser and go to the system URL (e.g., http://localhost:8000).
Enter your email and password.
Click "Login". If you don't have an account, click "Register here" (for students).
For students: Verify with IC number first.

2. Admin/Lecturer Module / 管理员/讲师模块
Admins manage everything. Lecturers have similar access but focused on courses.

Dashboard: After login, see the control panel. Click icons for tasks.
Register Account: Click "Register Account". Fill in name, email, role (Lecturer/Student), course, password. Click Submit.
Manage Users: Click "Manage Users". View list of users. Click Edit/Delete next to a user.
Import Students: Click "Import Student". Enter details or link to database for bulk import. Reset passwords if needed.
Manage Courses: Click "Manage Courses". Add new: Enter title, image, dates. View/edit existing courses.
Manage Assignments: Click "Add Assignment". Select course, enter title, description, upload file, due date. Submit.
Manage CU Activities: Click "Manage CU Activities". Add activity: Course, title, description, due date.
Manage Topics: In activities, add topics: Title, type, upload content.
Check Assignment Status: Click "Assignment Status". View submissions, grades, progress bar. Provide feedback/grades.
Logout: Click your name or logout button.

3. Student Module / 学生模块
Students focus on learning and assignments.

Verify/Register: If new, enter IC number to verify. Then login or register.
Dashboard: See enrolled courses. Click a course to view details.
View CU Activities & Topics: In course page, see topics/activities. Click to start.
Assignments: Click "Assignment" tab. See tasks, due dates, scores. Download attachments.
Upload Assignment: Click "Upload" for a task. Select file (PDF/DOCX, <10MB), click Submit.
Profile: View your name, email, courses. Update if allowed.
Logout: Click logout.

中文:
系统有管理员/讲师和学生模块。我们将一步步解释，假设你已登录。默认管理员凭证：邮箱 - admin@example.com，密码 - password（首次登录后更改）。
1. 登录（所有用户）

打开浏览器访问系统URL（例如http://localhost:8000）。
输入邮箱和密码。
点击“Login”。如果没有账户，点击“Register here”（针对学生）。
针对学生：先用IC号码验证。

2. 管理员/讲师模块
管理员管理一切。讲师有类似访问，但专注于课程。

仪表板: 登录后，看到控制面板。点击图标执行任务。
注册账户: 点击“Register Account”。填写姓名、邮箱、角色（Lecturer/Student）、课程、密码。点击Submit。
管理用户: 点击“Manage Users”。查看用户列表。点击用户旁的Edit/Delete。
导入学生: 点击“Import Student”。输入细节或链接数据库批量导入。需要时重置密码。
管理课程: 点击“Manage Courses”。添加新课程：输入标题、图像、日期。查看/编辑现有课程。
管理作业: 点击“Add Assignment”。选择课程，输入标题、描述、上传文件、截止日期。提交。
管理CU活动: 点击“Manage CU Activities”。添加活动：课程、标题、描述、截止日期。
管理主题: 在活动中添加主题：标题、类型、上传内容。
检查作业状态: 点击“Assignment Status”。查看提交、成绩、进度条。提供反馈/评分。
登出: 点击你的名字或登出按钮。

3. 学生模块
学生专注于学习和作业。

验证/注册: 如果是新用户，输入IC号码验证。然后登录或注册。
仪表板: 查看已注册课程。点击课程查看细节。
查看CU活动和主题: 在课程页面，看到主题/活动。点击开始。
作业: 点击“Assignment”标签。查看任务、截止日期、成绩。下载附件。
上传作业: 为任务点击“Upload”。选择文件（PDF/DOCX，<10MB），点击Submit。
个人资料: 查看姓名、邮箱、课程。如果允许，更新。
登出: 点击登出。



<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
