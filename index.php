<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu localhost</title>
    <!--Bootstrap 5 CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">

    <style>
        /* Fundo claro */
        body {
            background-color: #ffffff;
        }

        /* Textos em preto */
        body,
        body * {
            color: #000;
        }

        /* Tabela estilo claro */
        .table-light td,
        .table-light th {
            background-color: #f9f9f9;
            border-color: #ddd;
        }

        /* Links e botões no tema claro */
        .btn-primary,
        .btn-secondary,
        .badge-primary,
        .badge-secondary,
        .nav-link,
        .text-decoration-none,
        a:not([class*="btn"]) {
            color: #000;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container-md">
            <a class="navbar-brand" href="#"></a>
        </div>
    </nav>

    <main class="flex-shrink-0 mb-auto">
        <div class="container-md">
            <table class="table table-striped table-light">
                <thead>
                    <tr>
                        <th>Diretório</th>
                        <th>Status</th>
                        <th>Data de Modificação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Diretório atual
                    $dir = "./";
                    // Array de diretórios
                    $dirs = array_diff(scandir($dir), array('.', '..'));
                    // Ordenando os diretórios pela data de modificação mais recente
                    usort($dirs, function ($a, $b) use ($dir) {
                        return filemtime($dir . $b) - filemtime($dir . $a);
                    });
                    // Paginação
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $perPage = 10;
                    $start = ($page - 1) * $perPage;
                    $dirs = array_slice($dirs, $start, $perPage);
                    // Loop pelos diretórios
                    foreach ($dirs as $d) {
                        $modDate = filemtime($dir . $d);
                        $dateDiff = time() - $modDate;
                        $daysSinceMod = ceil($dateDiff / (60 * 60 * 24)); // Dias desde a última modificação

                        if ($daysSinceMod < 3) {
                            $badgeType = "success";
                        } elseif ($daysSinceMod < 7) {
                            $badgeType = "primary";
                        } else {
                            $badgeType = "secondary";
                        }


                        $sizeInBytes = filesize($dir . $d);
                        $sizeInMb = round($sizeInBytes / 1024 / 1024, 2);


                        if (is_file($dir . $d)) {
                            echo "<tr><td><a href='$d' class='fst-italic text-decoration-none'>$d</a></td>";
                        } else {
                            echo "<tr><td><a href='$d' class=\"text-decoration-none\">$d</a></td>";
                        }


                        echo "<td><span class='badge bg-$badgeType'>$daysSinceMod dias atrás</span></td>";

                        echo "<td>" . date("Y/m/d H:i", $modDate) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!--Paginação-->
            <?php
            $totalDirs = count(array_diff(scandir($dir), array('.', '..')));
            $pages = ceil($totalDirs / $perPage);
            if ($pages > 1) {
                echo "<nav aria-label='Paginação'><ul class='pagination justify-content-end'>";
                for ($i = 1; $i <= $pages; $i++) {
                    $activeClass = ($i == $page) ? " active" : "";
                    echo "<li class='page-item$activeClass'><a class='page-link' href='?page=$i'>$i</a></li>";
                }
                echo "</ul></nav>";
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-auto">
        <p class="text-center text-muted">
            &copy; <?php echo date("Y"); ?> - Todos os direitos reservados.
        </p>
    </footer>

    <!--Bootstrap 5 JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.min.js"></script>
</body>

</html>