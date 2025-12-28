# EcoRide - AI Agent Coding Guidelines

## Project Overview
EcoRide is a PHP-based carpooling (covoiturage) platform promoting eco-friendly transportation. The application connects drivers (chauffeurs) with passengers to share rides. Core entities: Users, Rides (covoiturage), Vehicles, Participation, Reviews, and Preferences.

## Architecture: Custom MVC Framework

### Core Layer Structure
- **Base Classes**: `Model.php`, `Controller.php`, `Auth.php`, `Session.php` in `app/core/`
- **Database**: Singleton pattern in `config/db.php` using PDO with MySQL
- **Models**: Extend `Model` class and use `$this->query()` for prepared statements
- **Views**: PHP templates in `app/views/` organized by feature (ride, auth, admin, etc.)

**Key Pattern**: Controllers instantiate models, call methods, then `require` views. NO autoloading - require statements are explicit.

### Models (Data Layer)
Reference files: [User.php](app/models/User.php), [Ride.php](app/models/Ride.php), [participation.php](app/models/participation.php)

Database table naming: lowercase with underscores (`utilisateur`, `covoiturage`, `vehicule`, `participation`)
- Column suffixes: `_id` for PKs, `utilisateur_id`/`covoiturage_id` for FKs
- **User Credits System**: Users start with 20 credits (US 2); deducted on ride participation
- **Ecological Flag**: Auto-calculated from vehicle energy type on ride creation
- **Ride Lifecycle**: Has timestamps (date/time for departure AND arrival)
- **Suspended Accounts**: Flag `compte_suspendu` prevents login

All models use PDO prepared statements: `$this->query($sql, [$param1, $param2])->fetch()` or `->fetchAll()`

### Controllers (Action Layer)
Reference: [CarController.php](app/controllers/CarController.php) (despite filename, it's `VehiculeController`)

**Session-First Guard Pattern**: 
```php
if (!Session::has("user_id")) {
    header("Location: login.php");
    exit;
}
```

**Role-Based Access**:
```php
$roleModel = new Role();
if (!$roleModel->userHasRole($userId, "chauffeur")) {
    die("Access denied");
}
```

Methods follow `action()` naming (e.g., `index()`, `add()`, `show()`). Each action instantiates models, processes logic, then `require` views.

### Authentication & Authorization
- **Auth Class**: Static methods `check()`, `requireLogin()`, `login($user)`, `logout()`, `hasRole($role)`
- **Session Class**: Static wrapper around `$_SESSION` with methods `get()`, `set()`, `has()`
- **Roles**: Stored in sessions; models have `userHasRole($userId, $roleString)` methods

Users authenticated via email/password; session stores `user_id`, `pseudo`, `roles` array.

## Database Design Patterns

### Junction Table - Participation
Links users to rides. Prevents duplicate joins via `hasJoined()` check. Stores `date_participation`.

### Joined Queries
Rides display driver pseudo + vehicle model:
```sql
SELECT c.*, u.pseudo, v.modele, v.energie 
FROM covoiturage c 
JOIN utilisateur u ON c.chauffeur_id = u.utilisateur_id 
JOIN vehicule v ON c.voiture_id = v.vehicule_id
```

### Logging Pattern
Some models (e.g., Ride.php) use file-based logging: `file_put_contents("Ride.log", "[$date] $message", FILE_APPEND)`

## Developer Workflows

### Database Setup
1. MySQL database named `ecoride`
2. Config: `config/db.php` (hardcoded: root user, no password)
3. No migrations framework - schema must be managed manually

### Entry Point
- [public/index.php](public/index.php): Includes config and routes requests (routing logic TBD - currently requires manual URL handling)
- **No framework router**: Controllers are invoked directly from views or via manual URL parsing

### Adding Features
1. **Model**: Create method in appropriate model class (e.g., add query to [Ride.php](app/models/Ride.php))
2. **Controller**: Add action method; instantiate model and call query; `require` view
3. **View**: Create PHP template in `app/views/{feature}/` directory
4. **Access Control**: Always start with `Session::has("user_id")` guard; add role checks if needed

### Testing & Debugging
- **No test framework** - manual verification required
- File-based logging in `.log` files (e.g., `Ride.log`)
- **Debug**: Check database state directly; read `.log` files for action trails
- **Error Handling**: Try/catch in database connection (config/db.php); most validation missing

## Code Conventions & Patterns

### Naming
- Controllers: `*Controller` class (despite file naming inconsistencies like `CarController` class in file)
- Views: kebab-case directory structure matching features (`ride/`, `auth/`, `admin/`)
- Database: lowercase snake_case (`covoiturage_id`, `utilisateur_id`)
- Models: PascalCase with plural/singular matching intent (`Participation`, `Ride`, `User`)

### Comments Style
Large section headers with visual separators (repeated `===` lines). Pattern:
```php
/* ============================================================
   SECTION NAME
   ============================================================ */
```

### Missing Practices
- No input validation layer (sanitize/validate as you go in controllers)
- No error handling middleware (check database directly on failures)
- No view templating engine (raw PHP `require` statements)
- No API responses standardization (methods return raw DB results)

## Common Data Flows

1. **User Registration** → User.php `register()` → Auto-created preferences row
2. **Search Rides** → Ride.php `searchRides()` → Returns joined data with driver/vehicle info
3. **Join Ride** → Participation.php `addParticipation()` → Deduct credits from user + increment ride occupancy
4. **Driver Creates Ride** → VehiculeController → Ride.php `addRide()` → Auto-detects ecological via vehicle energy type

## File Structure Reminders

- **Models must extend `Model`** and use `$this->query()` for DB access
- **Views in `app/views/{feature}/` folders** - no shared templates except `layouts/{header,footer}.php`
- **Controllers handle logic** but don't format responses - views handle display
- **Configuration centralized** in `config/` directory
- **Public assets** in `public/` (images, styles not yet structured)
