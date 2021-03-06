<?php
  require_once(__ROOT__.'/Api/BaseController.php');
  require_once(__ROOT__.'/Database/Models/Cliente.php');
  class ClientController extends BaseController {
    private $clienteDAO = null;
    public function __construct()
    {
      $this->clienteDAO = new Cliente();
    } 
    /** 
     * Listando a todos los clientes
    */
    public function index(){
      $requestMethod = $_SERVER["REQUEST_METHOD"];
      $clientes = array();
      if (strtoupper($requestMethod) == 'GET') {
        $page =  isset($_GET['page']) ? intval($_GET['page']) : 1;
        $clientes = $this->clienteDAO->all($page);
        $this->sendOutput(json_encode($clientes),array(
          'Access-Control-Allow-Origin:*',
          'Content-Type: application/json', 
          'HTTP/1.1 200 OK'
        ));
      } else {
        $error = $this->notSupported(); 
        $this->sendOutput($error['message'], $error['headers']);
      }

    }
     /** 
     * Listando pidiendo a un cliente en especifico
     * @param $cliente
    */
    public function show(){
      $requestMethod = $_SERVER["REQUEST_METHOD"];
      $cliente = array();
      if (strtoupper($requestMethod) == 'GET') {
        $id = isset($_GET['cliente'])?$_GET['cliente']:null;
        $cliente = $this->clienteDAO->findByID($id);
        if($cliente){
          $this->sendOutput(json_encode($cliente), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 200 OK'
          ));
        }else{
          $this->sendOutput(json_encode(['message'=>'usuario no encontrado']), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 404 NOT FOUND'
          ));
        }
      }else{
        $error = $this->notSupported(); 
        $this->sendOutput($error['message'], $error['headers']);
      }
    }
     /** 
     * Creando un cliente
     * @param $cliente (fillable)
    */
    public function create(){
      $requestMethod = $_SERVER["REQUEST_METHOD"];
      $cliente = array();
      if (strtoupper($requestMethod) == 'POST') {
        $cliente = array_filter($_POST, function($value, $key){
          return in_array($key, $this->clienteDAO->fillable());
        }, ARRAY_FILTER_USE_BOTH);
        if(!$cliente){
          $this->sendOutput(json_encode(['message'=>'No proporcionaste datos']), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 500 INTERNAL ERROR'
          ));
        }
        $status = $this->clienteDAO->create($cliente);
        if($status){
          $queryClient = $this->clienteDAO->findByID($this->clienteDAO->lastId());
          $this->sendOutput(json_encode($queryClient), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 201 CREATED'
          ));
        }else {
          $this->sendOutput(json_encode(['message'=>'Error en creacion']), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 500 INTERNAL ERROR'
          ));
        }
      }else{
        $error = $this->notSupported(); 
        $this->sendOutput($error['message'], $error['headers']);
      }
    }
    /** 
     * Eliminar un cliente
     * @param $cliente (fillable)
    */
    public function destroy(){
      $requestMethod = $_SERVER["REQUEST_METHOD"];
      $id = isset($_POST['id'])? $_POST['id']: 0;
      if (strtoupper($requestMethod) == 'POST') {
        $status = $this->clienteDAO->destroy($id);
        if($status){
          $this->sendOutput(json_encode(['id'=>$id,'deleted'=>true]), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 200 DELETED'
          ));
        }else {
          $this->sendOutput(json_encode(['message'=>'Error no se pudo eliminar']), array(
            'Access-Control-Allow-Origin:*',
            'Content-Type: application/json',  
            'HTTP/1.1 500 INTERNAL ERROR'
          ));
        }
      }else{
        $error = $this->notSupported(); 
        $this->sendOutput($error['message'], $error['headers']);
      }
    }
    private function notSupported(){
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
      return array(
        'message' =>json_encode(['message'=>$strErrorDesc]),
        'headers'=> array(
        'Access-Control-Allow-Origin:*',
        'Content-Type: application/json', 
        $strErrorHeader
        )
      );
    }
  }
?>