<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS para el Nav -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Menú del Restaurante</title>
</head>
<body>
    <?php
    if (file_exists('./datos/menus.xml')){
        $menu = simplexml_load_file('./datos/menus.xml');
    } else {
        exit('Error abriendo el archivo de datos');
    }

    // Coge el filtro de la URL
    $tipo_filtrado = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';

    // Agrupar platos por tipo respetando el filtro
    $platos_por_tipo = [];
    foreach ($menu->plato as $plato) {
        $tipo = (string)$plato['tipo'];
        // 
        if ($tipo_filtrado == 'todos' || $tipo_filtrado == $tipo) {
            $platos_por_tipo[$tipo][] = $plato;
        }
    }
    ?>

    <!-- Nav de Bootstrap adaptado -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <a class="navbar-brand" href="?tipo=todos">Restaurante Gourmet</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <!-- Opción para ver todos -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($tipo_filtrado == 'todos') ? 'active' : ''; ?>" href="?tipo=todos">Todos</a>
            </li>
            <?php
            $aux=[];
            // Recorremos el XML original para sacar las categorías únicas
            foreach($menu->plato as $plato){
                $tipo_plato = (string)$plato['tipo'];
                if(!in_array($tipo_plato, $aux)){
                    echo '<li class="nav-item">';
                    $active_class = ($tipo_filtrado == $tipo_plato) ? 'active' : '';
                    echo '<a class="nav-link '.$active_class.'" href="?tipo='.$tipo_plato.'">'.ucfirst($tipo_plato).'</a>';
                    echo '</li>';
                    array_push($aux, $tipo_plato);
                }
            }
            ?>
        </ul>
      </div>
    </nav>

    <h1>Menú del Restaurante</h1>
    
    <!-- Mantenemos tu estructura de clases intacta -->
    <div class="menu-container">
        <?php foreach ($platos_por_tipo as $tipo => $platos): ?>
            <h2><?php echo ucfirst($tipo); ?></h2>
            <div class="platos">
                <?php foreach ($platos as $plato): ?>
                    <div class="plato">
                        <img src="<?php echo $plato->img; ?>" alt="<?php echo $plato->nombre; ?>">
                        <h3><?php echo $plato->nombre; ?></h3>
                        <p class="precio">$<?php echo $plato->precio; ?></p>
                        <p><?php echo $plato->descrip; ?></p>
                        <p>Calorías: <?php echo $plato->calorias; ?></p>
                        <div class="caracteristicas">
                            <strong>Características:</strong>
                            <ul>
                                <li><?php echo $plato->caracteristicas->picante; ?></li>
                                <li><?php echo $plato->caracteristicas->salada; ?></li>
                                <li><?php echo $plato->caracteristicas->dulce; ?></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://kit.fontawesome.com/93bd960164.js" crossorigin="anonymous"></script>
    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>