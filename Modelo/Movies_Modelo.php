<?php
class Movies_Modelo
{
    private $db;
    private $movies;

    private $genres;

    public function __construct()
    {
        require_once("Modelo/Conexion.php");
        $this->db = Conectar::conexion();

    }
    public function get_genres()
    {
        $sql = "SELECT * FROM genre";

        $consulta = $this->db->query($sql);

        while($registro = $consulta->fetch_assoc())
        {
            $this->genres[] = $registro;
        }

        return $this->genres;
    }

    public function get_movies($offset, $limit = 20, $search = '', $genre = null)
    {
        $sql = "SELECT DISTINCT m.* FROM movie m 
        LEFT JOIN moviegenre mg ON m.id = mg.movie_id 
        WHERE m.title LIKE ?";
        if ($genre !== null) {
            $sql .= " AND mg.genre = ? LIMIT ?, ?";
            $consulta = $this->db->prepare($sql);
            $searchTerm = '%' . $search . '%';
            $consulta->bind_param("siii", $searchTerm, $genre, $offset, $limit);
        } else {
            $sql .= " LIMIT ?, ?";
            $consulta = $this->db->prepare($sql);
            $searchTerm = '%' . $search . '%';
            $consulta->bind_param("sii", $searchTerm, $offset, $limit);
        }

        $consulta->execute();
        $result = $consulta->get_result();

        $movies = [];
        while ($registro = $result->fetch_assoc()) {
            $movies[] = $registro;
        }
        return $movies;
    }

    public function get_movie_count($search ='', $genre = null)
    {
        $sql = "SELECT COUNT(DISTINCT m.id) as count FROM movie m 
        LEFT JOIN moviegenre mg ON m.id = mg.movie_id 
        WHERE m.title LIKE ?";
        if ($genre !== null) {
            $sql .= " AND mg.genre = ?";
            $consulta = $this->db->prepare($sql);
            $searchTerm = '%' . $search . '%';
            $consulta->bind_param("si", $searchTerm, $genre);
        } else {
            $consulta = $this->db->prepare($sql);
            $searchTerm = '%' . $search . '%';
            $consulta->bind_param("s", $searchTerm);
        }

        $consulta->execute();
        $result = $consulta->get_result();
        return $result->fetch_assoc()['count'];
    }

}
?>