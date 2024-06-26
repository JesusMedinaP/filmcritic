<?php
class Movies_Modelo
{
    private $db;
    private $movies;

    public function __construct()
    {
        require_once("Modelo/Conexion.php");
        $this->db = Conectar::conexion();

    }

    public function get_movies()
    {
        $sql = "SELECT * FROM movie";
        $consulta = $this->db->query($sql);

        while($registro = $consulta->fetch_assoc())
        {
            $this->movies[] = $registro;
        }
        return $this->movies;
    }
}
?>