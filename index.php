<?php
  define('__ROOT__', dirname(__FILE__));
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode( '/', $uri );
  // Router por acciones
  if ((isset($uri[2]) && $uri[2] != 'clientes') || !isset($uri[3])) {
      header("HTTP/1.1 404 Not Found");
      exit();
  }else{
    require_once(__ROOT__.'/api/ClientController.php');
    $objFeedController = new ClientController();
    $strMethodName = $uri[3];
    $objFeedController->{$strMethodName}();
  }
  
?>