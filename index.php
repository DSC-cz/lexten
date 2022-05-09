<?php
$maintenance = "";//"Web je momentálně nedostupný, pracuje se na opravách. <br/><br/>IP: 82.208.17.49:27479";

require('controllers/steamauth/steamauth.php');
require('functions.php');
require('models/Database.php');

try{
    $GLOBALS["databases"] = [0=>new Database(0), 1=>new Database(1), 2=>new Database(2)];
} catch (Exception $e){
    echo "Nepodařilo se připojit k databázím. ".$e->getMessage();
    exit();
}

mb_internal_encoding("UTF-8");

function nacteni($trida)
{  
if (preg_match('/Shortcode$/', $trida)){
    if(!file_exists("shortcodes/" . $trida . ".php")) throw new Exception("Shortcode '".str_replace('Shortcode', '', $trida)."' nebyl nalezen.");
    require('shortcodes/'.$trida.'.php');
}
else if (preg_match('/Controller$/', $trida)){
    if(!file_exists("controllers/" . $trida . ".php")) throw new Exception("Zadaná stránka neexistuje");
    require("controllers/" . $trida . ".php");
}else require("models/" . $trida . ".php");
}

spl_autoload_register("nacteni");


//if(User::isLoggedIn()) User::lastActive();

class Router extends BaseController{
    protected $controller;

    public function select($path){
        $path = str_replace("", "", $path);

        $path = str_replace(['.', '%'], '', parse_url($path)["path"]);

        $params = explode("/", trim(ltrim($path, "/")));

        $params[0] = str_replace("-", "", ucwords($params[0]));

        return array("controller"=>array_shift($params).'Controller', "params"=>$params);
    }

    public function error_redirect($message){
        $_SESSION['error_message'] = $message;
        header('Location: /chyba');
        header('Connection: close');
        exit;
    }
}

$router = new Router();
$controller = $router->select($_SERVER['REQUEST_URI'])["controller"];
$redirects = [
    "Zakoupenivip"=>"/clanek/zakoupeni-vip/",
    "Vip"=>"/clanek/zakoupeni-vip/",
    "Discord"=>"https://discord.gg/wjfRHm7",
];
if(isset($redirects[str_replace("Controller", "", $controller)])){
    header("Location: ".$redirects[str_replace("Controller", "", $controller)]); 
    exit();
}
if($controller == "Controller") $controller = "MainController"; 

if(!empty($maintenance)){
    if(!User::isModerator([1, 2])){
        try{
            $c = new MaintenanceController($router->select($_SERVER['REQUEST_URI'])["params"], $maintenance);

            require('views/default/header.phtml');
            echo '<body><main class="controller text-center">';
            $c->returnView();
            echo '</main></body>';
        } catch (Exception $e){
            echo $e->getMessage();
        }

        exit(); 
    }
}

try {
    $aside = array('Forum');
    $c = new $controller($router->select($_SERVER['REQUEST_URI'])["params"]);

    require('views/default/header.phtml');
    echo '<body>';
    require('views/default/layout.phtml');
    echo '<main class="container '.(in_array(str_replace("Controller", "", $controller), $aside) ? 'aside-content d-flex justify-content-around': '').'">';
    $c->returnView();
    echo "</main>";
    if(isset($_SESSION['flash_message']) || !empty($maintenance)){
        echo '<div class="flash_message '.(isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : '').'">'.(isset($_SESSION['flash_message']) ? $_SESSION['flash_message'] : 'Web je zapnut pouze pro moderátory, uživatelům se zobrazuje: '.$maintenance ).' <button class="close_flash btn btn-secondary">Zavřít</button></div>';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
    require('views/default/footer.phtml');
    echo '</body>';
}
catch(Exception $e){
    $router->error_redirect($e->getMessage());
}

?>