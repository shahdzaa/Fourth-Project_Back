# Building Damage Reporting System

This Laravel project implements a system for managing buildings, neighbourhoods, sectors, committees, engineers, and damage reports, based on the provided ERD.

## Database Structure

### Main Tables
- **sectors**: Holds sector names.
- **neighbourhoods**: Belongs to a sector. Holds neighbourhood names.
- **buildings**: Belongs to a neighbourhood. Stores building details (legal, number of floors, structural pattern, etc.).
- **foundations**: Belongs to a building. Stores foundation type.
- **damage_reports**: Belongs to a building. Stores damage report details (photo, degree, date, number).
- **committees**: Represents a committee.
- **engineers**: Stores engineer details (name, phone, address, age).
- **users**: Standard Laravel users table.

### Pivot Tables
- **committee_neighbourhood**: Many-to-many between committees and neighbourhoods.
- **committee_engineer**: Many-to-many between committees and engineers, with `dateOfAppointment`.

## Eloquent Model Relationships

- **Sector**
  - `hasMany(Neighbourhood)`
- **Neighbourhood**
  - `belongsTo(Sector)`
  - `hasMany(Building)`
  - `belongsToMany(Committee)`
- **Building**
  - `belongsTo(Neighbourhood)`
  - `hasMany(Foundation)`
  - `hasMany(DamageReport)`
- **Foundation**
  - `belongsTo(Building)`
- **DamageReport**
  - `belongsTo(Building)`
- **Committee**
  - `belongsToMany(Neighbourhood)`
  - `belongsToMany(Engineer)` (with pivot: `dateOfAppointment`)
- **Engineer**
  - `belongsToMany(Committee)` (with pivot: `dateOfAppointment`)

## Migrations
All migrations are set up with proper foreign keys and cascading deletes. Run:

```
php artisan migrate
```

to create the database tables.

## Usage
- Use Eloquent relationships for easy data access (e.g., `$building->neighbourhood`, `$committee->engineers`).
- Extend models as needed for business logic.

## Notes
- This project is based on Laravel. See the [Laravel documentation](https://laravel.com/docs) for framework usage.
