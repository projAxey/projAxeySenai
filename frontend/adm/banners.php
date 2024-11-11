<?php
include '../layouts/head.php';
include '../layouts/nav.php';
include '../../config/conexao.php';


if (isset($_POST['create_banner'])) {
    // Diretório onde as imagens serão salvas
    $target_dir = "../../assets/imgs/banners/";
    $target_file = $target_dir . basename($_FILES["banner_image"]["name"]);
    $target_name = "assets/imgs/banners/" . basename($_FILES["banner_image"]["name"]);

    // Move o arquivo para o diretório de destino
    if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
        $titulo_categoria = $_POST['titulo_categoria'];
        $dataIni = $_POST['dataIni'];
        $dataFim = $_POST['dataFim'];
        
        $sql = "INSERT INTO Banners  (image, legenda, data_inicial, data_final) VALUES ('$target_name', '$titulo_categoria', '$dataIni', '$dataFim')";
        if ($conexao->query($sql) === TRUE) {
            echo "Banner cadastrado com sucesso!";
            header("Location: /projAxeySenai/frontend/adm/admin.php");
            exit();
        } else {

        }
    } else {
        echo "Erro ao fazer upload do arquivo.";
    }
}

if (isset($_POST['delete_banner'])) {
    $delete_id = $_POST['delete_id'];
    
    $sql = "DELETE FROM Banners WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$delete_id]);

    $success = $stmt->rowCount() > 0;

    if ($success) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Banner excluído!',
                        text: 'O banner foi excluído com sucesso.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/projAxeySenai/frontend/adm/banners.php';
                        }
                    });
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Não foi possível excluir o banner.',
                        confirmButtonText: 'OK'
                    });
                });
              </script>";
    }
}


?>

<style>

.banners-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.banner-item {
    width: 100%;
    max-width: 300px;
    text-align: center;
}

.banner-item img {
    width: 100%;
    border-radius: 8px;
    margin-bottom: 10px;
}

</style>

<body>
    <main class="main-admin">
        <div class="container container-admin">
            <nav aria-label="breadcrumb-admin">
                <ol class="breadcrumb breadcrumb-admin">
                    <li class="breadcrumb-item">
                        <a href="admin.php" style="text-decoration: none; color:#012640;"><strong>Voltar</strong></a>
                    </li>
                </ol>
            </nav>
            <div class="title title-admin">GERENCIAR BANNERS</div>
            <div class="d-flex justify-content-between mb-4">
                <button type="button" id="meusAgendamentos" class="mb-2 btn btn-meus-agendamentos"
                    style="background-color: #012640; color:white" data-bs-toggle="modal" data-bs-target="#novoBannerModal"> Novo Banner <i class="bi bi-plus-circle"></i>
                </button>
            </div>
            <div class="banners-container">
    <ul class="list-group">
        <?php
        $sql = "SELECT * FROM Banners";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Formatando a data
                $dataFinal = new DateTime($row['data_final']);
                $dataFormatada = $dataFinal->format('d/m/Y');
                
                echo "<li class='list-group-item banner-item'>";
                
                // Imagem com tamanho específico
                echo "<img src='../../" . $row['image'] . "' alt='Banner' class='img-fluid' style='width: 180px; height: 40px;'>";
                
                // Botões de editar e excluir
                echo "<div class='d-flex justify-content-center mb-2'>";
                echo "<button class='btn btn-primary btn-sm me-2' data-bs-toggle='modal' data-bs-target='#editModal' 
                        data-id='" . $row['id'] . "' data-title='" . $row['legenda'] . "' data-final-date='" . $row['data_final'] . "'>Editar</button>";
                echo "<button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                        data-id='" . $row['id'] . "' data-title='" . $row['legenda'] . "'>Excluir</button>";
                echo "</div>";

                // Legenda e data final formatada
                echo "<p>" . $row['legenda'] . "</p>";
                echo "<p>Válido até: " . $dataFormatada . "</p>";
                echo "</li>";
            }
        } else {
            echo "<li class='list-group-item'>Nenhum banner cadastrado.</li>";
        }
        ?>
    </ul>
</div>
        </div>
    </main>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-title" class="form-label">Legenda</label>
                        <input type="text" class="form-control" id="edit-title">
                    </div>
                    <div class="mb-3">
                        <label for="edit-link" class="form-label">Data final</label>
                        <input type="text" class="form-control" id="edit-link">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar alterações</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir o link <span id="delete-title"></span>?</p>
                </div>
                <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    <form method="post" action="">
        <input type="hidden" name="delete_id" id="delete-id">
        <button type="submit" class="btn btn-danger" name="delete_banner">Excluir</button>
    </form>
</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="novoBannerModal" tabindex="-1" aria-labelledby="novoBannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="novoBannerModalLabel">Novo Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
    <div class="col-md-12 mb-3">
        <label for="bannerImage" class="form-label">Imagem</label>
        <input type="file" class="form-control" id="bannerImage" name="banner_image" accept="image/*" onchange="previewImages()">
        <div id="imagePreview" class="preview d-flex flex-wrap"></div>
    </div>

    <div class="mb-3">
        <label for="legendaBanner" class="form-label">Legenda</label>
        <input type="text" class="form-control" id="titulo_categoria" name="titulo_categoria">
    </div>
    <div class="mb-3">
        <label for="prazoBanner" class="form-label">Prazo</label>
        <div class="row">
            <div class="col">
                <input type="date" class="form-control" id="dataIni" name="dataIni">
            </div>
            <div class="col">
                <input type="date" class="form-control" id="dataFim" name="dataFim">
            </div>
        </div>
    </div>
    <button type="submit" name="create_banner" class="btn btn-primary">Criar</button>
</form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/previewImgs.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var finalDate = button.getAttribute('data-final-date');
        
        var titleInput = editModal.querySelector('#edit-title');
        var dateInput = editModal.querySelector('#edit-link');
        
        titleInput.value = title;
        dateInput.value = finalDate;
    });

    var deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var title = button.getAttribute('data-title');
    
    var modalBody = deleteModal.querySelector('.modal-body');
    modalBody.querySelector('#delete-title').textContent = title;

    deleteModal.querySelector('#delete-id').value = id;
});

        });
    </script>
</body>

</html>
</>