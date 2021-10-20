<?php 
  require_once(__ROOT__.'/examen/Database/Database.php');
  class Cliente extends Database {
    protected $fillable = [
      'nombre',
      'direccion',
      'pais',
      'telefono',
      // 'fecha_pedido',
      'vendedor',
      'region',
    ];
    private $limit_per_page = 10;
    function all($page = 1){
      $page = ($page - 1) * $this->limit_per_page;
      $query = $this
        ->connect()
        ->prepare("SELECT * FROM clientes ORDER BY id limit ${page},{$this->limit_per_page}");
      $query->execute();
      $clientes = array();
      if($query->rowCount()){
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
          $cliente = array(
            'id'=> $row['id'],
            'nombre'=> $row['nombre'],
            'direccion'=> $row['direccion'],
            'pais'=> $row['pais'],
            'telefono'=> $row['telefono'],
            'fecha_pedido'=> $row['fecha_pedido'],
            'vendedor'=> $row['vendedor'],
            'region'=> $row['region'],
          );
          array_push($clientes,$cliente);
        }
        return $clientes;
      }else{
        return [];
      }
    }
    function findByID($id){
      if(isset($id) && intval($id) <= 0){
        return [];
      }
      $stmt = $this->connect()->prepare("SELECT * FROM clientes WHERE id = {$id}");
      $stmt->execute();
      if($stmt->rowCount()){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return array(
          'id'=> $result['id'],
          'nombre'=> $result['nombre'],
          'direccion'=> $result['direccion'],
          'pais'=> $result['pais'],
          'telefono'=> $result['telefono'],
          'fecha_pedido'=> $result['fecha_pedido'],
          'vendedor'=> $result['vendedor'],
          'region'=> $result['region'],
        );
      }else{
        return null;
      }
    }
    function lastId(){
      $stmt = $this->connect()->prepare("SELECT MAX(id) as last_id FROM clientes");
      $stmt->execute();
      if($stmt->rowCount()){
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['last_id'];
      }else{
        return null;
      }
    }
    function create(array $clientProperties){
      $now = date('Y-m-d H:i:s');
      $query = "INSERT INTO clientes (nombre, direccion, pais, telefono, fecha_pedido, vendedor, region ) 
        VALUES (:nombre, :direccion, :pais, :telefono, '{$now}', :vendedor, :region)";
      $stmt = $this->connect()->prepare($query);
      return $stmt->execute($clientProperties);
    }
    function destroy($id){
      if(isset($id) && intval($id) <= 0){
        return false;
      }
      $cliente = $this->findByID($id);
      if(!$cliente){
        return false;
      }
      $stmt = $this->connect()->prepare("DELETE FROM clientes WHERE id = {$id}");
      return $stmt->execute();
    }
    function fillable(){
      return $this->fillable;
    }
    
  }
?>