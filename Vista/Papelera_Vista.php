<h2>Películas Eliminadas</h2>
<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($deleted_movies)){
            foreach($deleted_movies as $movie): ?>
            <tr>
                <td><?php echo $movie['title']; ?></td>
                <td>
                    <button onclick="restoreMovie(<?php echo $movie['id']; ?>)">Restaurar</button>
                    <button onclick="deleteMoviePermanently(<?php echo $movie['id']; ?>)">Eliminar Definitivamente</button>
                </td>
            </tr>
        <?php endforeach;
        } else {  echo 'No hay películas eliminadas en la base de datos o ha habido algún problema al conectarse'; } ?>
    </tbody>
</table>