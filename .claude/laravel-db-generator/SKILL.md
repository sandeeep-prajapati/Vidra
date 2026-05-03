---
name: laravel-db-generator
description: Generate Laravel migrations, models, factories, seeders, and relationships from markdown table definitions in the DB_Structure folder. Use for creating database schema and Eloquent models for the school management system.
---

# Laravel DB Generator Skill

This skill helps generate Laravel database-related code from the structured markdown files in the DB_Structure folder.

## Usage

- Read the relevant .md file from DB_Structure/
- Generate migration files in `laravel/database/migrations/`
- Generate model files in `app/Models/` (or within the package under `app/Packages/{Module}/src/Models/`)
- Add relationships, fillables, etc.
- Optionally generate factories and seeders.

## Example: Student Management Module

Based on 01_Student_Management.md, created:
- Tables: students, parents, previous_educations, health_records
- Models: Student, ParentInfo, PreviousEducation, HealthRecord
- Relationships: Student hasOne ParentInfo, hasMany PreviousEducation, hasOne HealthRecord

## Goals

- Automate the creation of database schema for modules like Student Management, Staff, Classes, etc.
- Ensure proper foreign keys and constraints.
- Create Eloquent models with relationships.