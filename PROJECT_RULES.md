# 🧠 PROJECT RULES – Laravel Gestion Notes System

## 🎯 Project Goal

This is a **School Management System** built with Laravel 12.

It manages:

* Students (Etudiants)
* Teachers
* Classes
* Subjects (Matieres)
* Evaluations
* Notes (Grades)
* Roles & Permissions

---

## 👥 Roles & Permissions

### 1. Admin

* Full access
* Manage users, classes, subjects, evaluations
* Assign teachers to subjects/classes
* View statistics

### 2. Teacher

* Add/edit notes
* View assigned classes & subjects
* Cannot manage users

### 3. Student

* View own notes
* View subjects
* Cannot modify anything

---

## 🏗️ Architecture Rules

### Controllers

* Must stay thin
* Business logic → Models or Services

### Models

* Must contain relationships
* Example:

  * Etudiant → belongsTo Utilisateur
  * Note → belongsTo Etudiant & Evaluation

### Views (Blade)

* Only UI logic
* No database queries

---

## ⚠️ Important Constraints

* NEVER break role system
* NEVER remove validation
* NEVER duplicate logic
* ALWAYS reuse existing methods
* ALWAYS check relationships before coding

---

## 🔥 Critical Business Logic

### Notes

* One student = one note per evaluation
* Max note = 20

### Classes

* Have capacity (max_students)
* Must NOT exceed capacity

### Users

* Email auto-generated for students/teachers
* email_locked = true for them

---

## 🧹 What Should NOT Exist

* Duplicate controllers
* Unused models
* Dead routes
* Hardcoded values

---

## 🚀 Coding Standards

* Use Laravel best practices
* Use Eloquent relationships
* Validate all inputs
* Use clean naming

---

## 🧠 AI Behavior Rules

When modifying code:

1. Read existing code first
2. Identify exact issue
3. Modify ONLY necessary parts
4. Keep code clean and simple
5. Do NOT rewrite whole project

---

## 📌 Example Good Fix

✅ Fix:

* Modify only NoteController store()

❌ Bad:

* Rewrite Note system completely

---

## 📊 Relationships Summary

* Utilisateur → hasMany Roles
* Etudiant → belongsTo Utilisateur
* Note → belongsTo Etudiant
* Note → belongsTo Evaluation
* Evaluation → belongsTo Matiere & Classe

---

## 🧠 Final Rule

If unsure → STOP and ASK.
Never guess.

