<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author Cesar
 */

require 'constantes.php';

class Conexion {
    
    public  $mysqli;
    
    public function __construct() {
        $this->mysqli = new mysqli(SERVER,USER,PASSWORD,BD);
        $this->mysqli ->query("SET NAMES 'utf8'");
    }
}
