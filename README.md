# Teacher Platform

Teacher Platform is an educational management system designed to provide teachers and students with a centralized environment for managing courses, learning materials, assessments, and student performance.

The platform is being developed with a focus on keeping the learning workflow simple for students while giving teachers the tools required to manage and evaluate their courses effectively.

## Overview

The platform supports two main roles:

* **Teachers** - manage courses, educational content, assessments, and student submissions.
* **Students** - access enrolled courses, study course materials, complete assessments, and review their results.

The system is designed so that the core educational workflow is handled within the platform rather than relying on separate tools for each part of the process.

## Core Features

### Course Management

Teachers can create and manage courses and their associated educational content.

A course can contain different types of materials, including:

* Video lessons
* Markdown-based lessons
* Assessments

Courses can also be published and managed throughout their lifecycle.

### Assessments

The assessment system supports multiple question types:

* Multiple Choice
* True / False
* Short Answer

Questions can have different point values and can be ordered within an assessment.

Assessments also support time limits and submission deadlines.

### Submissions

When a student starts an assessment, a submission is created and used to track the student's answers and progress.

The submission lifecycle is handled explicitly:

```text
Started
   ↓
In Progress
   ↓
Submitted
   ↓
Graded
```

The system prevents changes to submissions after they have been finalized.

### Grading

The grading system supports both automatic and manual evaluation.

Automatically gradable answers are evaluated by the system, while answers requiring teacher review remain pending until they are manually graded.

The final result is calculated from the points earned across the submission.

### Student Progress

Assessment progress is preserved so that students can continue an unfinished assessment without losing their current answers.

The system also handles assessment deadlines and can finalize a submission when its allowed time has expired.

## Architecture

The application follows a full-stack architecture built around Laravel and Inertia.js.

```text
React Client -> Inertia -> Laravel (
    Controllers
    Form Requests
    Models
    Services
    Policies
) -> MySQL
```

The backend remains responsible for authorization, validation, assessment state, grading, and other business rules. Client-side state is used to improve the user experience but is not treated as the source of truth.

## Technology

### Backend

* PHP
* Laravel
* Eloquent ORM
* MySQL

### Frontend

* React
* TypeScript
* Inertia.js

## Assessment Workflow

The general assessment workflow is:

```text
Student
   │
   ▼
Start Assessment
   │
   ▼
Create Submission
   │
   ▼
Answer Questions
   │
   ▼
Submit
   │
   ▼
Grade
   │
   ├── Automatic
   └── Manual
   │
   ▼
Calculate Result
   │
   ▼
Finalize Submission
```

The backend validates each important transition to ensure that a submission cannot be modified or submitted after reaching its final state.

## Project Status

The project is currently under active development.

The current focus is on completing the core learning and assessment workflows and establishing a solid foundation for future features.

## Planned Development

Future development may include:

* Attendance management
* QR / barcode-based attendance
* Real-time dashboards
* Notifications
* Student and course analytics
* Payment integration
* Background processing
* Search and indexing
* API access for external clients

## Development

Install the project dependencies:

```bash
composer install
npm install
```

Create the environment configuration:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database in `.env`, then run the migrations:

```bash
php artisan migrate
```

Start the development server:

```bash
php artisan serve
```

Run the frontend development server:

```bash
npm run dev
```

## License

This project is publicly available for viewing and reference purposes only.

The source code may not be copied, modified, distributed, or used in other projects, commercial products, or services without prior written permission from the author.

For permission to use any part of this project, please contact the author.
