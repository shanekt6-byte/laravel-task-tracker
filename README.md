# Laravel Blade Task Tracker

A lightweight, clean Task Tracker web application built with **Laravel 13**, **Blade**, and **SQLite**.

Built as part of a technical practical assessment demonstrating core Laravel concepts:
- Database Migrations & Schema Design
- Eloquent ORM & Mass Assignment Protection
- RESTful Web Routes & Route Model Binding
- Server-side Form Validation & Session Flash Messages
- Laravel Blade Templates with CSRF Security & HTTP Method Spoofing
- Automated Feature Testing (PHPUnit)

---

## Features

1. **Create New Task**:
   - Required Title with max-length validation.
   - Optional Description.
   - Default status set automatically to `pending`.
2. **View All Tasks**:
   - Clean, responsive task list displaying task ID, title, description, status badge, and timestamps.
3. **Toggle Task Status**:
   - One-click bidirectional status toggle between `pending` (amber badge) and `completed` (green badge).
   - Validated strictly against permitted values (`in:pending,completed`).
4. **Delete Task**:
   - RESTful deletion with confirmation dialog and flash notification.
5. **CSRF & Security**:
   - All forms protected with `@csrf`.
   - Method spoofing using `@method('PATCH')` and `@method('DELETE')`.

---

## Technical Stack

- **Framework**: Laravel 13.x
- **PHP**: 8.5+
- **Database**: SQLite (serverless, file-based)
- **Frontend**: Laravel Blade templates with clean modern styling

---

## Setup & Running Locally

1. **Clone the repository**:
   ```bash
   git clone https://github.com/<your-username>/laravel-task-tracker.git
   cd laravel-task-tracker
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

5. **Start Development Server**:
   ```bash
   php artisan serve
   ```
   Visit: `http://127.0.0.1:8000`

---

## Running Automated Tests

Run the full test suite (8 tests covering validation, state transitions, creation, and deletion):
```bash
php artisan test
```
All tests should pass with 100% green assertions.
