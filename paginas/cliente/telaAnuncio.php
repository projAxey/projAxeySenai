<?php

class Page {
    public function render() {
        $this->head();
        echo '<body class="bodyCards">';
        $this->nav();
        echo '<div class="main-container">';
        $this-> Container();
        $this->footer();
        echo '</div>';
        echo $this->getScripts();
        echo '</body></html>';
    }

    private function head() {
        include '../../padroes/head.php';
    }
    

    private function nav() {
        include '../../padroes/nav.php';
    }

    private function Container() {
        echo '<div class="py-3">
                <div class="main container d-flex flex-column flex-md-row justify-content-between">';
        $this->carousel();          // Coluna 1: Carousel
        $this->mainGroup();         // Coluna 2: Descrição do Prestador
        echo '</div></div>';
        $this->servicesSection();
    }

    private function carousel() {
        $carousel_images = [
            "../../assets/imgs/imgTeste.png",
            "../../assets/imgs/imgTeste.png",
            "../../assets/imgs/imgTeste.png"
        ];

        echo '<div id="separa-divs" class="carousel-container flex-grow-1 me-md-3">';
        echo '    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">';
        echo '        <div class="carousel-inner">';

        foreach ($carousel_images as $index => $image) {
            echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                    <img src="' . $image . '" class="carousel-img" alt="...">
                </div>';
        }

        // Avançar ou retroceder
        echo '  </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>';
    }

    // MainGroup
    private function mainGroup() {
        echo '<div class="main-group-func container flex-wrap object-fit d-flex align-self-center">
                <div class="legenda container text-center mb-3">
                    <p>Descricao do produto Descricao do produto Descricao do produto Descricao do produto Descricao do produto Descricao do produto Descricao do produto Descricao do produto</p>
                </div>
                <div class="buttom-group d-flex flex-column container text-center">
                    <div class="group-button d-flex flex-column py-2">
                        <a type="submit" class="btn btn-primary">Verificar disponibilidade</a>
                    </div>
                </div>';
        
        // Chamada ao prestadorGroup logo após o botão
        $this->prestadorGroup();  
        
        echo '</div>';
    }
    
    // prestador
    private function prestadorGroup() {
        echo '<div class="prestador-card d-flex flex-column align-items-center text-center border p-3 rounded shadow-sm mt-3" style="width: 400px;">';
        
        // Parte do avatar
        echo '  <div class="mb-2">';
        echo '      <img src="../../assets/imgs/ruivo.png" alt="Foto do Prestador" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">';
        echo '  </div>';
    
        // Nome do prestador
        echo '  <div>';
        echo '      <strong style="font-size: 1rem;">João Antonio da Rosa</strong>';
        echo '  </div>';
    
        // Quantidade de serviços
        echo '  <div class="text-muted">';
        echo '      <small>45 serviços</small>';
        echo '  </div>';
    
        // Botão para ver mais serviços
        echo '  <div class="mt-2">';
        echo '      <a href="../../paginas\cliente\telaServicosPrestador.php" class="btn btn-primary" border: 2%; padding: 5px 20px; font-size: 1rem;">Ver mais serviços do prestador</a>';
        echo '  </div>';
        
        echo '</div>';
    }
    
    // Serviços
    private function servicesSection() {
        $servicos = [
            1 => "Serviço 1",
            2 => "Serviço 2",
            3 => "Serviço 3",
            4 => "Serviço 4",
            5 => "Serviço 5",
            6 => "Serviço 6",
            7 => "Serviço 7",
            8 => "Serviço 8"
        ];

        echo '<div class="services-container-wrapper container containerCards">
                <div class="tituloServicos">
                    <h3 class="titulo">Serviços em destaque</h3>
                </div>
                <button class="arrow fechaEsquerda flecha" onclick="scrollCards(\'.container1\', -1)">&#9664;</button>
                <div class="services-container container1 containerServicos">';

        foreach ($servicos as $id => $title) {
            echo '<div class="card cardServicos">
                    <img src="../../assets/imgs/testeimg2.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">' . $title . '</h5>
                        <p class="card-text">Descrição breve do ' . $title . '.</p>
                        <a href="paginas/cliente/telaAnuncio.php" class="btn btn-primary btnSaibaMais">Saiba mais</a>
                    </div>
                </div>';
        }

        echo '  </div>
                <button class="arrow flechaDireita flecha" onclick="scrollCards(\'.container1\', 1)">&#9654;</button>
            </div>';
    }

    private function footer() {
        include '../../padroes/footer.php';
    }

    // Cards
    private function getScripts() {
        return '
            <script>
                function scrollCards(containerSelector, direction) {
                    const container = document.querySelector(containerSelector);
                    const cardWidth = container.querySelector(\'.cardServicos\').offsetWidth;
                    container.scrollBy({
                        left: direction * cardWidth,
                        behavior: \'smooth\'
                    });
                }
            </script>';
    }
}

$page = new Page();
$page->render();
