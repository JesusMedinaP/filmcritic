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
                WHERE m.title LIKE ?";
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


    public function get_movie_count($search ='', $genre = null)
    {
        $sql = "SELECT COUNT(DISTINCT m.id) as count 
        FROM movie m 
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