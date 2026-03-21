 <?php  


// /www/index.php
// Error reporting (DEV MODE)
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// BASE PATH
define("BASE_PATH", __DIR__ );
// BASE URL (when app is served from a subdirectory)
define("BASE_URL", '/ecoride');



// Core
require_once './app/core/db.php';
require_once './app/core/Session.php';
require_once './app/core/Auth.php';
require_once './app/core/Model.php';
// Models

require_once './app/models/User.php';
require_once './app/models/Ride.php';
require_once './app/models/Participation.php';
require_once './app/models/Review.php';
require_once './app/models/Car.php';
require_once './app/models/Preference.php';
//var_dump(BASE_PATH);

// Controllers
require_once './app/controllers/AuthController.php';
require_once './app/controllers/RideController.php';
require_once './app/controllers/ParticipationController.php';
require_once './app/controllers/ReviewController.php';
require_once './app/controllers/AdminController.php';
require_once './app/controllers/EmployeeController.php';
require_once './app/controllers/CarController.php';
require_once './app/controllers/PreferenceController.php';
require_once './app/controllers/UserController.php';
//var_dump(BASE_PATH);

// ----------------------------------
// SIMPLE ROUTING (NO Router class)
// ----------------------------------

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// HOME (ROOT)
if ( $uri === '/ecoride' || $uri === '/ecoride/') {//$uri === '/' ||

    if (Session::has("user_id")) {
        // Logged user → ride search / list
        (new RideController())->index();
    } else {
        // Guest → login
        (new AuthController())->login();
    }
      exit;
}



// AUTH
if ($uri === '/ecoride/login') {
    (new AuthController())->login();
}
elseif ($uri === '/ecoride/register') {
    (new AuthController())->register();
}
elseif ($uri === '/ecoride/logout') {
    (new AuthController())->logout();
}

// RIDES
elseif ($uri === BASE_URL . '/ride/create') {
    (new RideController())->create();
}
elseif ($uri === BASE_URL . '/ride/store' && $method === 'POST') {
    (new RideController())->store();
}
elseif ($uri === BASE_URL . '/ride/history') {
    (new RideController())->history();
}
elseif ($uri === BASE_URL . '/ride/show') {
    (new RideController())->show();
}

// RIDE List (alias) - support /ecoride/ride
elseif ($uri === BASE_URL . '/ride' || $uri === BASE_URL . '/ride/') {
    (new RideController())->index();
}

// PARTICIPATION
elseif ($uri === BASE_URL . '/participation/join') {
    (new ParticipationController())->join();
}
elseif ($uri === BASE_URL . '/participation/cancel') {
    (new ParticipationController())->cancel();
}

// VEHICULES
elseif ($uri === BASE_URL . '/vehicule' || $uri === BASE_URL . '/vehicule/') {
    (new VehiculeController())->index();
}
elseif ($uri === BASE_URL . '/vehicule/create') {
    (new VehiculeController())->addForm();
}
elseif ($uri === BASE_URL . '/vehicule/store' && $method === 'POST') {
    (new VehiculeController())->add();
}

// PREFERENCES (chauffeurs)
elseif ($uri === BASE_URL . '/preferences') {
    (new PreferenceController())->index();
}
elseif ($uri === BASE_URL . '/preferences/save' && $method === 'POST') {
    (new PreferenceController())->save();
}
elseif ($uri === BASE_URL . '/preferences/custom/add' && $method === 'POST') {
    (new PreferenceController())->addCustom();
}
elseif ($uri === BASE_URL . '/preferences/custom/delete' && $method === 'GET') {
    (new PreferenceController())->deleteCustom();
}

// USER - choose role
elseif ($uri === BASE_URL . '/user/choose-role') {
    (new UserController())->chooseRole();
}
elseif ($uri === BASE_URL . '/user/update-role' && $method === 'POST') {
    (new UserController())->updateRole();
}

// AVIS
elseif ($uri === BASE_URL . '/avis/create') {
    (new AvisController())->add();
}
// ADMIN / EMPLOYEE (minimal)/
elseif ($uri === BASE_URL . '/admin') {
   (new AdminController())->index();}
elseif ($uri === BASE_URL . '/admin/create' && $method === 'POST') {
    (new AdminController())->createEmployee();
}
elseif ($uri === BASE_URL . '/admin/suspend' && $method === 'POST') {
    (new AdminController())->suspend();
}
elseif ($uri === BASE_URL . '/admin/activate' && $method === 'POST') {
    (new AdminController())->activate();
}
elseif ($uri === BASE_URL . '/employee') {
    (new EmployeeController())->index();}
elseif ($uri === BASE_URL . '/employee/validateAvis' && ($method === 'POST' || $method === 'GET')) {
    (new EmployeeController())->validateAvis();
}
elseif ($uri === BASE_URL . '/employee/refuseAvis' && ($method === 'POST' || $method === 'GET')) {
    (new EmployeeController())->refuseAvis();
}
else {
        http_response_code(404);
        require BASE_PATH . '/app/views/errors/404.php';
};



// DEFAULT


