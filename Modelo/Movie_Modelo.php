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

    public function get_user_score($userId, $movieId)
    {
        $sql = "SELECT us. score FROM user_score us WHERE us.id_user = ? AND us.id_movie = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("ii", $userId, $movieId);
        $consulta->execute();

        $result = $consulta->get_result();
        $score = $result->fetch_assoc();
        return $score;
    }

    public function submit_score($userId, $movieId, $score)
    {
        $timestamp = date('Y-m-d H:i:s');

        $sql = "REPLACE INTO user_score(id_user, id_movie, score, time) VALUES (?, ?, ?, ?)";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("iiis", $userId, $movieId, $score, $timestamp);
        $consulta->execute();
    }

    public function submit_comment($userId, $movieId, $comment)
    {
        $sql = "INSERT INTO moviecomments (movie_id, user_id, comment) VALUES (?, ?, ?)";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("iis", $movieId, $userId, $comment);
        $consulta->execute();
    }

}
?>