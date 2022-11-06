<?php 

class Router
{

    // $routes -- массив в котор.хранится маршруты
    private $routes;

    public function __construct()
    {
        //  указываем путь к routes.php (root -- путь к базовой директории, + путь созд.файлу)
        $routesPath = ROOT.'/config/routes.php';
        //  присваеваем к свойству routes - массив, который наход. в routes.php
        $this->routes = include($routesPath);
    }

    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    // run() -- принимает отправление от FRONT CONTROLLER
    public function run()
    {
        //  получить строку запроса
        $uri = $this->getURI();

        //  проверка такого запроса в routes.php
        foreach($this->routes as $uriPattern => $path) {
            //  сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {

                //  Находим внутренний путь
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                
                //  Определить какой controller и action запустить
                // $segments -- разделяет url на две части., где 1 -- controller, 2 -- action (пр: news(1) / views(2))
                $segments = explode('/', $internalRoute);

                //  получаем имя controller             array_shift -- получает перв.элем.массива и его удаляет его из массива
                $controllerName = array_shift($segments).'Controller';
                // имя controller делаем с большой буквы (тк .php имя контроллера записанно именно с большой)
                $controllerName = ucfirst($controllerName);

                // получем имя action + большая буква
                $actionName = 'action'.ucfirst(array_shift($segments));

                $parameters = $segments;
  
                //  подключить controller
                $controllerFile = ROOT . '/controllers/'. $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                //  созд.обьект класса Controller, и метод
                $controllerObject = new $controllerName;
                //  фунц. вызыв. action(>actionName), у обькта controllerObject + передает ему $parameters >> 
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                if ($result != null) {
                    break;
                }
            }
        }
    }

}