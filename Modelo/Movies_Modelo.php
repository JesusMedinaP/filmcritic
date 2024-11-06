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

    public function get_movies($offset, $limit = 20, $search = '', $genre = null, $order = "DESC")
    {
        $order = ($order == 'DESC') ? 'ASC' : 'DESC'; // Asegura que solo se use 'ASC' o 'DESC'
    
        $sql = "SELECT m.id, m.title, m.date, m.url_imdb, m.url_pic, m.desc,
                       ms.average_score AS avg_score, ms.total_votes AS score_count
                FROM movie m
                LEFT JOIN moviegenre mg ON m.id = mg.movie_id
                LEFT JOIN movie_score ms ON m.id = ms.id_movie
                WHERE m.title LIKE ? AND m.deleted_at IS NULL";
        
        if ($genre !== null) {
            $sql .= " AND mg.genre = ?";
        }
        
        $sql .= " GROUP BY m.id ORDER BY m.id $order LIMIT ?, ?";
    
        $consulta = $this->db->prepare($sql);
        $searchTerm = '%' . $search . '%';
    
        if ($genre !== null) {
            $consulta->bind_param("siii", $searchTerm, $genre, $offset, $limit);
        } else {
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

    public function get_movie_genre($movie_id) {
        $query = "SELECT genre FROM moviegenre WHERE movie_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all();
    }


    public function get_movie_count($search ='', $genre = null)
    {
        $sql = "SELECT COUNT(DISTINCT m.id) as count 
        FROM movie m 
        LEFT JOIN moviegenre mg ON m.id = mg.movie_id 
        WHERE m.title LIKE ? AND m.deleted_at IS NULL";
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

    public function insert_movie($title, $date, $url_imdb, $url_pic, $description)
    {
        $query = "INSERT INTO movie (title, date, url_imdb, url_pic, `desc`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssss', $title, $date, $url_imdb, $url_pic, $description);
        $stmt->execute();
    
        // Retornar el ID de la película insertada
        return $stmt->insert_id;
    }

    public function insert_movie_genre($movie_id, $genre_id)
    {
        $query = "INSERT INTO moviegenre (movie_id, genre) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $movie_id, $genre_id);
        $stmt->execute();
    }

    public function update_movie($id, $title, $date, $url_imdb, $url_pic, $desc) {
        $sql = "UPDATE movie SET title = ?, date = ?, url_imdb = ?, url_pic = ?, `desc` = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $date, $url_imdb, $url_pic, $desc, $id]);
    }

    function update_movie_genres($movie_id, $genres) {
        // Eliminar géneros actuales
        $sql = "DELETE FROM moviegenre WHERE movie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movie_id]);
        $executed = true;
    
        // Insertar nuevos géneros
        $sql = "INSERT INTO moviegenre (movie_id, genre) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($genres as $genre_id) {
            if(!$stmt->execute([$movie_id, $genre_id]))
            {
                $executed = false;
            }
        }
        return $executed;
    }

    public function soft_delete_movie($movie_id) {
        $query = "UPDATE movie SET deleted_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $movie_id);
        return $stmt->execute();
    }

    public function get_deleted_movies($search, $offset, $limit) {
        $query = "SELECT * FROM movie WHERE deleted_at IS NOT NULL AND title LIKE ? LIMIT ?, ?";
        $search_param = '%' . $search . '%';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii', $search_param, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_deleted_movies_raw()
    {
        $query = "SELECT * FROM movie WHERE deleted_at IS NOT NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_deleted_movie_count($search ='', $genre = null)
    {
        $sql = "SELECT COUNT(DISTINCT m.id) as count 
        FROM movie m 
        LEFT JOIN moviegenre mg ON m.id = mg.movie_id 
        WHERE m.title LIKE ? AND m.deleted_at IS NOT NULL";
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

    public function restore_movie($movie_id) {
        $query = "UPDATE movie SET deleted_at = NULL WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $movie_id);
        return $stmt->execute();
    }

    public function delete_movie_permanently($movie_id) {
        $query = "DELETE FROM movie WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $movie_id);
        return $stmt->execute();
    }

}
?>