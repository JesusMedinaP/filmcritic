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

    public function get_movies($offset, $limit = 20)
    {
        $sql = "SELECT * FROM movie LIMIT ?, ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("ii", $offset, $limit);
        $consulta->execute();
        $result = $consulta->get_result();

        while($registro = $result->fetch_assoc())
        {
            $this->movies[] = $registro;
        }
        return $this->movies;
    }
}
?>