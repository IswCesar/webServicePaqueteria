<?php
//require '../Config/Conexion.php';

class Consultas {
    
    private $conexion;
    private $results;
    private $mysqli;
    
    public function __construct() {
       $this -> conexion = new Conexion();
       $this -> mysqli = $this->conexion->mysqli;
    }
    
    public function insertarUsuario($values)
    {
        $query = "insert into usuario values('','$values[clave]','$values[nombre]','$values[correo]','$values[direccion]','$values[telefono]')";
        
        if($res = $this -> mysqli -> query($query))
        {
            return true;
        }
        return false;
    }
    
    public function insertarPedido($values)
    {
        $query = "insert into paquete values('','$values[peso]','$values[dimensiones]','$values[origen]','$values[destino]','$values[tipo]','$values[fechaEntrega]','$values[costo]')";
        date_default_timezone_set('America/Mexico_City');
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $a = false;
        $b = false;
        if($res = $this -> mysqli -> query($query))
        {
            $a = true;
        }
        $last_index = $this -> mysqli -> insert_id;
        $query2 = "insert into pedido values('','$time','$date',$values[idusuario],$last_index)";
        
        if($res = $this -> mysqli -> query($query2))
        {
            $b = true;
        }
        if($a && $b)
            return true;
        return false;
    }
    
    public function insertarMovimiento($values)
    {
        $aux = "SELECT MAX(estatus_has_pedido.idestatus) FROM estatus_has_pedido WHERE estatus_has_pedido.idpedido = '$values[idpedido]'";
        print $aux;
        $res = $this -> mysqli -> query($aux);
        $fila = $res->fetch_assoc();
        $i =  $fila['MAX(estatus_has_pedido.idestatus)'];
        $i += 0;
        $j = 4;
        print $i;
        if($i == $j)
        {
            return false;
        }
        else
        {
            date_default_timezone_set('America/Mexico_City');
            $time = date("H:i:s");
            $query = "insert into movimiento values ('','$time','$values[descripcion]','$values[tipo]','$values[idusuario]','$values[idpedido]')";
            if($res = $this -> mysqli -> query($query))
            {
                return true;
            }
            return false;
        }
    }
    
    public function consultarMovimientos($id){
        $query = "SELECT hora , descripcion FROM movimiento INNER JOIN pedido ON movimiento.idpedido = pedido.idpedido AND movimiento.idpedido ='".$id."'";
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this->results;
    }    
    
    public function loggin($values)
    {
        $query = "SELECT * from usuario where correo = '".$values['usuario']."' and clave = '".$values['clave']."'";
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this -> results;
    }

    public function ConsultarPedido(){
        $query = "SELECT * FROM pedido";
        $res = $this -> mysqli -> query($query);
        $this -> results = $res -> fetch_all(MYSQLI_ASSOC);
        return $this->results;
    } 
    
    
}

/*
$miConsulta = new Consultas();

$values[peso] = "1kg";
$values[dimensiones] = "10x20x30";
$values[origen] = "Penjamo";
$values[destino] = "Cortazar";
$values[tipo] = "Normal";
$values[fechaEntrega] = "14/07/17";
$values[costo] = "120";
$values[horaPedido] = "1:30";
$values[fechaPedido] = "14/07/17";
$values[idusuario] = 1;
$values[idpaquete] = 1;
  
$values[descripcion] = "ENTREGA FALLIDA";
$values[tipo] = 4;
$values[idusuario] = 1;
$values[idpedido] = 2;
$miConsulta ->insertarMovimiento($values);*/