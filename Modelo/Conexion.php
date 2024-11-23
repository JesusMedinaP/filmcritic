<?php 

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'filmcritic');

    class Conectar
    {
        public static function conexion()
        {
            try
            {
                $conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            }
            catch(Exception $e)
            {
                die('Error:' . $e->getMessage());
            }
            return $conexion;
        }
    }
?>