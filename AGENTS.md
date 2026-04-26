# AGENTS.md — GestionNotes (ETU Note)
> Read this file **completely and carefully** before touching any code.
> Violating any rule here will break the system.

---

## 1. Project Overview

**ETU Note** is a school grade management system built with **Laravel 12**, using Blade templates, TailwindCSS v3, AlpineJS, and Vite. The database is **MySQL** (configured in `.env`).

The system manages: Students (Étudiants), Teachers (Professeurs), Classes, Subjects (Matières), Evaluations, Grades (Notes), Specializations, Levels, and Role-based Users.

**Tech Stack:**
- PHP ^8.2, Laravel 12, Laravel Breeze (Blade stack)
- TailwindCSS v3, AlpineJS v3, Vite 7
- MySQL (production), SQLite (tests only)
- No additional packages beyond `composer.json`

---

## 2. Authentication & User Model

The authentication model is **`App\Models\Utilisateur`** — NOT Laravel's default `User`. This is critical.

```
Table: utilisateurs
Auth guard: web (configured in config/auth.php to use Utilisateur)
```

**Key fields on `Utilisateur`:**
- `uuid` — auto-generated on create
- `nom`, `prenom` — last/first name (no `name` field)
- `email`, `password`, `telephone`, `etat` (actif/inactif/suspendu)
- `email_locked` (boolean) — if true, email cannot be changed
- `temporary_password` (nullable) — plaintext copy shown in admin UI
- `email_verified_at`, `remember_token`, `deleted_at` (SoftDeletes)

**Important methods on `Utilisateur`:**
- `isAdmin()`, `isTeacher()`, `isStudent()`, `isManager()` — role checks
- `hasRole($role)` — accepts string or array of role codes
- `canEditEmail()` — returns false if `email_locked` is true
- `generateTeacherEmail()` — generates `PR{7digits}@etu-note.ma`
- `generateStudentEmail()` — generates `TE{7digits}@etu-note.ma`
- `generateSecurePassword()` — generates 12-char secure password

**NEVER** use `App\Models\User` — it does not exist.
**NEVER** add a `name` field — the model uses `nom` and `prenom` separately.

---

## 3. Roles System

Roles are stored in the `roles` table. The `role_utilisateur` pivot table links users to roles.

| Role Code | Description |
|-----------|-------------|
| `admin` | Full system access |
| `teacher` | Can enter/edit grades, view assigned classes/subjects |
| `student` | Read-only access to own grades and evaluations |

**There is NO `manager` role** — it was migrated to `admin` in `2026_04_21_123442_convert_manager_users_to_admin.php`. Any code referencing `manager` should redirect to `admin` behavior.

**Role middleware:** `RoleMiddleware` is registered as `role` alias in `bootstrap/app.php`.

Usage in routes: `Route::middleware('role:admin')` or `Route::middleware('role:admin,teacher')`

**Checking roles in Blade:** `Auth::user()->hasRole(['admin'])` or `Auth::user()->isAdmin()`

---

## 4. Database Structure & Key Relationships

### Core Tables

```
utilisateurs          — All users (admin, teacher, student)
roles                 — Role definitions (admin, teacher, student)
role_utilisateur      — Pivot: users ↔ roles
etudiants             — Student profiles (linked to utilisateurs)
classes               — Class groups
matieres              — Subjects/courses
evaluations           — Assessment events (linked to matiere + classe)
notes                 — Individual grades (linked to etudiant + evaluation)
inscriptions          — Student enrollment per class per year
specializations       — Academic tracks (Informatique, Maths, etc.)
levels                — Year levels within specializations
teacher_classe        — Pivot: teachers ↔ classes
teacher_matiere       — Pivot: teachers ↔ matieres ↔ classes (3-way)
teacher_specialization — Pivot: teachers ↔ specializations
matiere_classe        — Pivot: matieres ↔ classes
```

### Model Relationships

**Utilisateur (teacher/admin):**
- `roles()` → BelongsToMany → Role (via `role_utilisateur`)
- `etudiant()` → HasOne → Etudiant
- `classes()` → BelongsToMany → Classe (via `teacher_classe`)
- `specializations()` → BelongsToMany → Specialization (via `teacher_specialization`)
- `matieres()` → BelongsToMany → Matiere (via `teacher_matiere`, with pivot `classe_id`)
- `matiereClasses()` → HasMany → TeacherMatiere

**Etudiant:**
- `utilisateur()` → BelongsTo → Utilisateur
- `classe()` → BelongsTo → Classe
- `notes()` → HasMany → Note
- `inscriptions()` → HasMany → Inscription
- Computed: `getFullNameAttribute()`, `getMoyenneAttribute()`

**Note:**
- `etudiant()` → BelongsTo → Etudiant
- `evaluation()` → BelongsTo → Evaluation
- `utilisateurSaisie()` → BelongsTo → Utilisateur (FK: `utilisateur_saisie_id`)
- **UNIQUE constraint:** `(etudiant_id, evaluation_id)` — one grade per student per evaluation

**Evaluation:**
- `matiere()` → BelongsTo → Matiere
- `classe()` → BelongsTo → Classe
- `notes()` → HasMany → Note

**Classe:**
- `etudiants()` → HasMany → Etudiant
- `evaluations()` → HasMany → Evaluation
- `matieres()` → BelongsToMany → Matiere (via `matiere_classe`)
- `teachers()` → BelongsToMany → Utilisateur (via `teacher_classe`)
- `specialization()` → BelongsTo → Specialization
- `level()` → BelongsTo → Level
- Methods: `isFull()`, `hasSpace()`, `availableSlots()`, `incrementStudentCount()`, `decrementStudentCount()`

---

## 5. Business Rules — NEVER VIOLATE THESE

### Grades (Notes)
- **Max grade is 20** (validated in NoteController: `max:20`)
- **One grade per student per evaluation** — enforced by DB unique constraint AND controller logic
- Grade entry for a batch uses `storeSaisir` → `updateOrCreate` pattern
- Grades are linked to evaluations which have their own `note_max` and `coefficient`

### Classes (Capacity)
- Every class has `student_count` and `max_students` (default 20)
- When adding a student to a class: call `$classe->incrementStudentCount()`
- When removing a student or changing class: call `$oldClasse->decrementStudentCount()`
- **NEVER add a student to a full class** — always check `$classe->isFull()` first
- The Classe model has `isFull()`, `hasSpace()`, `availableSlots()` — use them

### Users (Email/Password)
- Students and Teachers get **auto-generated emails** (format: `TE/PR{7digits}@etu-note.ma`)
- Their `email_locked = true` — they CANNOT change their email
- Their email field in profile is `readonly` in the UI
- `temporary_password` is stored as plaintext for admin display only
- Admin users (`email_locked = false`) CAN change their email

### Inscriptions (Enrollments)
- The `saisir` grade entry flow uses `Inscription` records to find enrolled students:
  - Filters by `classe_id`, `annee_scolaire`, `statut = 'active'`
  - Students not enrolled via `Inscription` will NOT appear in grade entry

---

## 6. Route Structure

All routes are in `routes/web.php` and `routes/auth.php`.

```
GET  /                          → welcome view (public)
GET  /dashboard                 → DashboardController (redirects by role)

# Admin only (middleware: role:admin)
GET  /dashboard/admin           → Dashboard\AdminController@index
GET  /utilisateurs              → UtilisateurController (CRUD)
GET  /teacher                   → Teacher\TeacherController (CRUD)
GET  /etudiants                 → EtudiantController (CRUD)
GET  /matieres                  → MatiereController (CRUD)
GET  /specializations           → Specialization\SpecializationController (CRUD)
GET  /levels                    → Level\LevelController (CRUD)
GET  /classes                   → Classe\ClasseController (CRUD)
POST /admin/assignSubject       → AdminController@assignSubject
POST /admin/remove-subject      → AdminController@removeSubject

# Admin + Teacher (middleware: role:admin,teacher)
GET  /evaluations               → EvaluationController (CRUD)
GET  /evaluations/{id}/saisir   → NoteController@saisir     (named: notes.saisir)
POST /evaluations/{id}/saisir   → NoteController@storeSaisir (named: notes.storeSaisir)
GET  /notes                     → NoteController (index, show, edit, update)

# Teacher only (middleware: role:teacher)
GET  /dashboard/teacher         → Dashboard\TeacherController@index
POST /teacher/update-grades     → Dashboard\TeacherController@updateGrades

# Student only (middleware: role:student)
GET  /dashboard/student         → Dashboard\StudentController@index
GET  /bulletin                  → StudentController@bulletin  (named: student.bulletin)
GET  /schedule                  → StudentController@schedule  (named: student.schedule)

# Auth (common)
GET/POST /profile               → ProfileController
```

**Named routes for classes resource use parameter `classe`** (not `classes`):
```php
Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe'])
```
So: `route('classes.show', $classe->id)` is correct.

---

## 7. Controllers — Architecture Rules

Controllers are organized into subdirectories:

```
app/Http/Controllers/
├── Controller.php               (abstract base)
├── DashboardController.php      (invokable, redirects by role)
├── EtudiantController.php
├── EvaluationController.php
├── MatiereController.php
├── NoteController.php
├── ProfileController.php
├── UtilisateurController.php
├── Auth/                        (Breeze auth controllers — DO NOT MODIFY)
├── Classe/
│   └── ClasseController.php
├── Dashboard/
│   ├── AdminController.php
│   ├── StudentController.php
│   └── TeacherController.php
├── Level/
│   └── LevelController.php
├── Specialization/
│   └── SpecializationController.php
├── Student/
│   └── StudentController.php    (separate from Dashboard\StudentController)
└── Teacher/
    └── TeacherController.php    (manages teacher CRUD — separate from Dashboard\TeacherController)
```

**Note the duplication:** `Dashboard\TeacherController` handles the teacher dashboard view. `Teacher\TeacherController` handles teacher CRUD (index/create/store/show/edit/update/destroy). These are separate and both needed.

Similarly: `Dashboard\StudentController` handles the student dashboard/bulletin/schedule. `Student\StudentController` handles the student's own view (dashboard, grades, classmates). Both exist.

**Controller rules:**
- Keep controllers thin — no business logic
- Validate all inputs
- Check permissions (role) before destructive actions
- Always use `Auth::id()` for `utilisateur_saisie_id` when saving notes

---

## 8. Views Structure

All Blade views are in `resources/views/`:

```
layouts/
  app.blade.php       — Main authenticated layout (includes navigation)
  guest.blade.php     — Login/register layout
  navigation.blade.php — Top navigation bar (role-conditional links)
components/           — Reusable Blade components (DO NOT MODIFY)
auth/                 — Breeze auth views (DO NOT MODIFY)
dashboard/            — admin.blade.php, student.blade.php, teacher.blade.php, bulletin.blade.php, schedule.blade.php
classes/              — index, show, create, edit
etudiants/            — index, show, create, edit
evaluations/          — index, show, create, edit
matieres/             — index, show, create, edit
notes/                — index, show, create, edit, saisir
teacher/              — index, show, create, edit, dashboard
student/              — dashboard.blade.php
specializations/      — index, show, create, edit
levels/               — index, create, edit
utilisateurs/         — index, show, create, edit
profile/              — edit.blade.php + partials
```

**Layout conventions:**
- Authenticated pages use `<x-app-layout>` with `<x-slot name="header">`
- Guest pages use `<x-guest-layout>`
- Forms always include `@csrf`
- PUT/DELETE forms include `@method('PUT')` / `@method('DELETE')`
- AlpineJS `x-data` is used for toggle UIs (e.g., show/hide passwords)

---

## 9. What MUST NOT Be Changed

- **`app/Http/Controllers/Auth/`** — All Breeze auth controllers. Do not touch.
- **`resources/views/auth/`** — Auth views. Do not touch.
- **`resources/views/components/`** — Blade components. Do not touch.
- **`config/auth.php`** — Auth guard points to `Utilisateur`. Do not change.
- **`bootstrap/app.php`** — Role middleware alias. Do not change.
- **`app/Providers/AppServiceProvider.php`** — Sets `Schema::defaultStringLength(191)`. Required for MySQL indexes. Do not remove.
- **Database migrations** — Never edit existing migrations. Create new ones if schema changes are needed.
- **The `notes` table unique constraint** `(etudiant_id, evaluation_id)` — Critical business rule.
- **`email_locked` logic** — Students and teachers must never have editable emails.
- **Role codes** — `admin`, `teacher`, `student` are hardcoded throughout. Do not rename.

---

## 10. What Needs Fixing / Known Issues

When given an issue to fix, look here first for context:

1. **`teacher/create.blade.php` and `teacher/edit.blade.php`** reference `$spec->libelle` but `Specialization` model uses `name` (not `libelle`). This will throw errors. Fix: use `$spec->name`.

2. **`teacher/index.blade.php` "Ajouter" button** uses a broken pattern (submits a hidden form via GET). Fix: change to a direct `<a href="{{ route('teacher.create') }}">` link.

3. **`resources/views/teacher/show.blade.php`** references `$mc->matiere->libelle` and `$mc->classe->libelle`. Ensure `TeacherMatiere` relationships are loaded (`with('matiere', 'classe')`) in `Teacher\TeacherController@show`.

4. **`notes/saisir.blade.php`** only shows students enrolled via `Inscription` records. If students are assigned directly to a class (`classe_id` on `etudiants`) but not in `inscriptions`, they won't appear. This is a design limitation — do not "fix" by bypassing the inscription check; instead, ensure inscriptions are created when students are assigned to classes.

5. **`Student\StudentController`** references routes `student.grades` and `student.classmates` which are NOT defined in `routes/web.php`. These views (`student/grades.blade.php`, `student/classmates.blade.php`) are also missing. Either add the routes/views or remove the links.

6. **`ProfileTest.php`** uses `App\Models\User` which doesn't exist (should be `Utilisateur`). Tests will fail. Fix: update test to use `Utilisateur::factory()`.

7. **`PermissionController.php` and `PermissionRoleController.php` and `RoleController.php`** are empty stubs. Do not add logic to them unless explicitly asked.

---

## 11. Coding Standards

- **PHP 8.2+** — use typed properties, match expressions, arrow functions where appropriate
- **Eloquent** — always use relationships, never raw queries for relational data
- **Validation** — always validate in controllers using `$request->validate([...])`
- **Naming:** Models are PascalCase, tables are snake_case plural. Columns use French names (`nom`, `prenom`, `libelle`, `annee_scolaire`).
- **Blade:** No DB queries in views. Pass data from controllers only.
- **Security:** Always check `$user->hasRole([...])` before destructive operations in controllers.
- **Error handling:** Use `return redirect()->back()->withErrors([...])` for validation errors. Use `abort(403)` for unauthorized access.

---

## 12. Environment & Setup

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Default admin credentials (from DatabaseSeeder)
Email: admin@admin.com
Password: password

# Build assets
npm run build

# Development server
composer run dev  # runs artisan serve + queue + pail + vite concurrently
```

**Database:** Set `DB_CONNECTION=mysql` and configure `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in `.env`.

**Tests:** Use SQLite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:` — already set in `phpunit.xml`).

---

## 13. Prompt Template for Issues

When I give you an issue, respond with a precise OpenCode prompt using this format:

---

**OPENCODE PROMPT:**

```
Context: [specific files involved]
Issue: [exact problem description]
Root Cause: [what is wrong and why]
Fix Required:
  1. File: [path]
     Change: [specific change]
  2. File: [path]
     Change: [specific change]
Constraints:
  - Do NOT modify: [list files that must not change]
  - Do NOT break: [specific behaviors to preserve]
  - Validate: [what to check after the fix]
```

---

## 14. Quick Reference — Common Patterns

### Creating a student (admin only)
```php
// 1. Generate credentials
$email = Utilisateur::generateStudentEmail();
$password = Utilisateur::generateSecurePassword();

// 2. Create user
$user = Utilisateur::create([...email, password, email_locked: true, temporary_password: $password]);

// 3. Assign student role
$studentRole = Role::where('code', 'student')->first();
$user->roles()->sync([$studentRole->id]);

// 4. Create student profile
$etudiant = Etudiant::create([...'utilisateur_id' => $user->id]);

// 5. If class assigned, increment count
if ($request->classe_id) {
    $classe = Classe::find($request->classe_id);
    if ($classe->isFull()) { return error; }
    $classe->incrementStudentCount();
}
```

### Saving grades (batch)
```php
// Use updateOrCreate to handle re-entry
Note::updateOrCreate(
    ['etudiant_id' => $id, 'evaluation_id' => $evalId],
    ['note' => $value, 'utilisateur_saisie_id' => Auth::id()]
);
```

### Role-based view conditionals
```blade
@if(Auth::user()->hasRole(['admin']))
    {{-- admin only content --}}
@endif

@if(Auth::user()->isTeacher())
    {{-- teacher content --}}
@endif
```

### Loading relationships (avoid N+1)
```php
// Always eager load in controllers
Etudiant::with(['utilisateur', 'classe', 'notes.evaluation.matiere'])->get();
Note::with(['etudiant.utilisateur', 'evaluation.matiere'])->paginate(15);
```
