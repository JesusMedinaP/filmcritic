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

    public function get_movies($offset, $limit = 20, $search = '')
    {
        $sql = "SELECT * FROM movie WHERE title LIKE ? LIMIT ?, ?";
        $consulta = $this->db->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $consulta->bind_param("sii", $searchTerm, $offset, $limit);
        $consulta->execute();
        $result = $consulta->get_result();

        while($registro = $result->fetch_assoc())
        {
            $this->movies[] = $registro;
        }
        return $this->movies;
    }

    public function get_movie_count($search ='')
    {
        $sql = "SELECT COUNT(*) as count FROM movie WHERE title LIKE ?";
        $consulta = $this->db->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $consulta->bind_param("s", $searchTerm);
        $consulta->execute();
        $result = $consulta->get_result();
        return $result->fetch_assoc()['count'];
    }
}
?>