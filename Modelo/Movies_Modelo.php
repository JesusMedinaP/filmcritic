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
        $order = ($order == 'DESC') ? 'ASC' : 'DESC'; // Ensure only 'ASC' or 'DESC' is allowed
        $sql = "SELECT m.id, m.title, m.date, m.url_imdb, m.url_pic, m.desc
                FROM movie m
                LEFT JOIN moviegenre mg ON m.id = mg.movie_id 
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
        $movieIds = [];
        while ($registro = $result->fetch_assoc()) {
            $movies[] = $registro;
            $movieIds[] = $registro['id'];
        }

        if (count($movieIds) > 0) {
            $idPlaceholders = implode(',', array_fill(0, count($movieIds), '?'));
            $scoreSql = "SELECT id_movie, AVG(score) AS avg_score, COUNT(score) AS score_count 
                        FROM user_score 
                        WHERE id_movie IN ($idPlaceholders) 
                        GROUP BY id_movie";
            $scoreConsulta = $this->db->prepare($scoreSql);
            $scoreConsulta->bind_param(str_repeat('i', count($movieIds)), ...$movieIds);
            $scoreConsulta->execute();
            $scoreResult = $scoreConsulta->get_result();

            $scores = [];
            while ($scoreRegistro = $scoreResult->fetch_assoc()) {
                $scores[$scoreRegistro['id_movie']] = $scoreRegistro;
            }

            foreach ($movies as &$movie) {
                $movieId = $movie['id'];
                if (isset($scores[$movieId])) {
                    $movie['avg_score'] = $scores[$movieId]['avg_score'];
                    $movie['score_count'] = $scores[$movieId]['score_count'];
                } else {
                    $movie['avg_score'] = null;
                    $movie['score_count'] = 0;
                }
            }
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

    public function get_deleted_movies($offset, $limit) {
        $query = "SELECT * FROM movie WHERE deleted_at IS NOT NULL LIMIT ?, ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $offset, $limit);
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