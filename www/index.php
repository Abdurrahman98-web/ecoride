 <?php  
//require BASE_PATH . '/config/database.php';
 echo "PUBLIC INDEX OK";
// /www/index.php
session_start();

// BASE PATH
define('BASE_PATH', __DIR__);

// Error reporting (DEV MODE)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Core
require BASE_PATH . '/core/db.php';
require BASE_PATH . '/core/Session.php';
require BASE_PATH . '/core/Auth.php';

// Models
require BASE_PATH . '/app/models/Model.php';
require BASE_PATH . '/app/models/User.php';
require BASE_PATH . '/app/models/Ride.php';
require BASE_PATH . '/app/models/Participation.php';
require BASE_PATH . '/app/models/Avis.php';
require BASE_PATH . '/app/models/Vehicule.php';
require BASE_PATH . '/app/models/Preference.php';

// Controllers
require BASE_PATH . '/app/controllers/AuthController.php';
require BASE_PATH . '/app/controllers/RideController.php';
require BASE_PATH . '/app/controllers/ParticipationController.php';
require BASE_PATH . '/app/controllers/AvisController.php';
require BASE_PATH . '/app/controllers/AdminController.php';
require BASE_PATH . '/app/controllers/EmployeeController.php';

// ----------------------------------
// SIMPLE ROUTING (NO Router class)
// ----------------------------------

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// AUTH
if ($uri === '/login') {
    (new AuthController())->login();
}
elseif ($uri === '/register') {
    (new AuthController())->register();
}
elseif ($uri === '/logout') {
    (new AuthController())->logout();
}

// RIDES
elseif ($uri === '/ride/create') {
    (new RideController())->create();
}
elseif ($uri === '/ride/store' && $method === 'POST') {
    (new RideController())->store();
}
elseif ($uri === '/ride/history') {
    (new RideController())->history();
}
elseif ($uri === '/ride/show') {
    (new RideController())->show();
}

// PARTICIPATION
elseif ($uri === '/participation/join') {
    (new ParticipationController())->join();
}
elseif ($uri === '/participation/cancel') {
    (new ParticipationController())->cancel();
}

// AVIS
elseif ($uri === '/avis/create') {
    (new AvisController())->add();
}

// ADMIN / EMPLOYEE (minimal)
elseif ($uri === '/admin') {
   // (new AdminController())->dashboard();
}
elseif ($uri === '/employee') {
    //(new EmployeeController())->dashboard();
}

// DEFAULT
else {
    http_response_code(404);
    require BASE_PATH . '/app/views/errors/404.php';
}

