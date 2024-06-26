<?php
class Users_Modelo
{
    private $db;
    private $users;
    private $ocupations;

    public function __construct()
    {
        require_once("Modelo/Conexion.php");
        $this->db = Conectar::conexion();

    }

    public function get_users()
    {
        $sql = "SELECT * FROM users";
        $consulta = $this->db->query($sql);

        while($registro = $consulta->fetch_assoc())
        {
            $this->users[] = $registro;
        }
        return $this->users;
    }

    public function get_ocupations()
    {
        $sql = "SELECT COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'ocupacion'";

        $consulta = $this->db->query($sql);

        if ($consulta->num_rows > 0) {
            $row = $consulta->fetch_assoc();
            $enum_str = $row['COLUMN_TYPE'];
            preg_match('/enum\((.*)\)$/', $enum_str, $matches);
            $this->ocupations = str_getcsv($matches[1], ',', "'");
        }

        return $this->ocupations;
    }
}
?>