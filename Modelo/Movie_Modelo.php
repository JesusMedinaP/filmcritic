<?php
class Movie_Modelo
{
    private $db;
    private $movie;

    public function __construct()
    {
        require_once("Modelo/Conexion.php");
        $this->db = Conectar::conexion();

    }

    public function get_movie($id)
    {
        $sql = "SELECT * FROM movie WHERE id = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
    
        $result = $consulta->get_result();
    
        if ($result->num_rows == 1) {
            return $result->fetch_assoc(); // Devuelve el registro como array asociativo
        } else {
            return null; // Manejar el caso donde no se encontró la película
        }
    }

    public function get_movie_genres($id)
    {
        $sql = "SELECT g.name 
                FROM moviegenre mg 
                JOIN genre g ON mg.genre = g.id 
                WHERE mg.movie_id = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
    
        $result = $consulta->get_result();
        $genres = [];
    
        while ($row = $result->fetch_assoc()) {
            $genres[] = $row['name'];
        }
    
        return $genres;
    }

    public function get_movie_comments($id)
    {
        $sql = "SELECT mc.comment, u.name 
                FROM moviecomments mc 
                JOIN users u ON mc.user_id = u.id 
                WHERE mc.movie_id = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
    
        $result = $consulta->get_result();
        $comments = [];
    
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
    
        return $comments;
    }

}
?>