<?php
include 'frontend/layouts/nav.php';
include 'frontend/layouts/head.php';
include 'config/conexao.php';
?>

<body class="bodyCards">
    <div class="main-container">
        <div class="container-fluid p-0">

            <!-- Carrossel -->
            <div id="carouselExampleIndicators" class="carousel slide carrosselServicos mb-2">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active carrosselItem">
                        <img class="d-block w-100" src="assets/imgs/banner.png" alt="Primeiro slide">
                    </div>
                    <div class="carousel-item carrosselItem">
                        <img class="d-block w-100" src="assets/imgs/banner.png" alt="Segundo slide">
                    </div>
                    <div class="carousel-item carrosselItem">
                        <img class="d-block w-100" src="assets/imgs/banner.png" alt="Terceiro slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </a>
            </div>

            <?php
            // Consulta para pegar as categorias
            $query = "SELECT categoria_id, titulo_categoria, icon FROM Categorias";
            $stmt = $conexao->prepare($query);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Se não houver categorias, exibir mensagem
            if (empty($categories)) {
                echo '<div class="container-fluid categorias mb-4"><p>Nenhuma categoria encontrada.</p></div>';
                return;
            }

            // Exibir categorias com setas laterais
            echo '<div class="container-fluid categorias mb-2">';
            echo '<button class="seta-esquerda" style="display:none;">&#9664;</button>'; // Seta para a esquerda, inicialmente oculta
            echo '<div class="categorias-container d-flex flex-nowrap justify-content-start">';

            foreach ($categories as $category) {
                $url = 'frontend/cliente/todosServicos.php?categoria_id=' . $category['categoria_id'];

                echo "
        <a href='{$url}' class='category-card cardsCategorias p-2' style='text-decoration: none;'>
            <div class='category-icon iconeCategoria'>
                <i class='" . htmlspecialchars($category['icon']) . "'></i>
            </div>
            <div class='mt-2'>{$category['titulo_categoria']}</div>
        </a>";
            }

            echo '</div>';
            echo '<button class="seta-direita">&#9654;</button>'; // Seta para a direita
            echo '</div>';


            // Exibir seções de serviços
            servicesSection("Serviços em destaque", getServicesDestques(), "servicos-em-destaque");
            servicesSection("Serviços disponíveis", getServices(), "servicos-mais-visitados");

            function servicesSection($title, $services, $sectionId)
            {
                echo "<div id='{$sectionId}' class='services-container-wrapper container containerCards mb-4'>";
                echo "<div class='tituloServicos'><h2>{$title}</h2></div>";
                echo '<div class="d-flex align-items-center">';
                echo "<button class='arrow flechaEsquerda flecha me-2'>&#9664;</button>";
                echo '<div class="services-container containerServicos d-flex">';


                foreach ($services as $service) {
                    // Verifica se a coluna de imagem contém mais de uma imagem separada por vírgula
                    $imagens = explode(',', $service['imagem_produto']);
                    // Pega apenas a primeira imagem da lista
                    $primeiraImagem = trim($imagens[0]);

                    echo "
    <div class='card cardServicos mx-2'>
        <img src='/projAxeySenai/{$primeiraImagem}' alt='Imagem do produto'>
        <div class='card-body'>
            <h5 class='card-title-servicos'>{$service['nome_produto']}</h5>
            <p class='card-text-servicos'>{$service['titulo_categoria']}</p>
            <a href='/projAxeySenai/frontend/cliente/telaAnuncio.php?id={$service['produto_id']}' class='btn btn-primary btnSaibaMais'>Saiba mais</a>
        </div>
    </div>";
                }

                echo '</div>';
                echo "<button class='arrow flechaDireita flecha ms-2'>&#9654;</button>";
                echo '</div></div>';
            }


            function getServices()
            {
                include 'config/conexao.php';

                $query = "SELECT c.titulo_categoria, p.produto_id, p.prestador, p.categoria, p.tipo_produto, p.nome_produto, p.valor_produto, p.descricao_produto, p.imagem_produto 
                FROM Produtos p 
                JOIN Categorias c ON p.categoria = c.categoria_id  
                WHERE p.status = 2 AND p.categoria_produto = 1";

                $stmt = $conexao->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os produtos


            }

            function getServicesDestques()
            {
                include 'config/conexao.php';

                $query = "SELECT c.titulo_categoria, p.produto_id, p.prestador, p.categoria, p.tipo_produto, p.nome_produto, p.valor_produto, p.descricao_produto, p.imagem_produto 
                FROM Produtos p 
                JOIN Categorias c ON p.categoria = c.categoria_id  
                WHERE p.status = 2 AND p.categoria_produto = 2";

                $stmt = $conexao->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os produtos
            }



            ?>

        </div>

    </div>
    <script src="assets/js/servicos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.categorias-container');
            const leftArrow = document.querySelector('.seta-esquerda');
            const rightArrow = document.querySelector('.seta-direita');

            // Inicialmente, esconde a seta esquerda se não houver necessidade de rolar para a esquerda
            checkScrollPosition();

            function scrollLeft() {
                container.scrollLeft -= 200; // Rolagem para a esquerda
                checkScrollPosition();
            }

            function scrollRight() {
                container.scrollLeft += 200; // Rolagem para a direita
                checkScrollPosition();
            }

            // Função para verificar a posição de rolagem e mostrar/ocultar setas
            function checkScrollPosition() {
                // Esconde a seta esquerda se o container estiver no início
                if (container.scrollLeft === 0) {
                    leftArrow.style.display = 'none';
                } else {
                    leftArrow.style.display = 'block';
                }

                // Esconde a seta direita se o container estiver no fim
                if (container.scrollLeft + container.clientWidth >= container.scrollWidth) {
                    rightArrow.style.display = 'none';
                } else {
                    rightArrow.style.display = 'block';
                }
            }

            // Adiciona os eventos aos botões de seta
            leftArrow.addEventListener('click', scrollLeft);
            rightArrow.addEventListener('click', scrollRight);

            // Chama a função ao redimensionar a janela para ajustar a visibilidade das setas
            window.addEventListener('resize', checkScrollPosition);
        });
    </script>
    <?php
    include 'frontend/layouts/footer.php';
    ?>
</body>