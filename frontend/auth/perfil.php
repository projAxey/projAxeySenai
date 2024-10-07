<?php
include '../layouts/head.php';
include '../layouts/nav.php';
require_once '../../config/conexao.php';
// Exemplo: Supondo que você já tenha uma conexão aberta com o banco em $conexao

// Verifica se o ID do cliente foi passado (por exemplo, por meio de sessão ou URL)
if ($_SESSION['tipo_usuario'] == 'cliente') {
    $cliente_id = $_SESSION['id'] ?? null;

    if ($cliente_id) {
        // Busca os dados do cliente no banco de dados
        $sql = "SELECT * FROM Clientes WHERE cliente_id = :cliente_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        // Obtém os dados do cliente
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        $nomeSocialPreenchido = !empty($cliente['nome_social']);
        // Verifica se encontrou o cliente
        if (!$cliente) {
            die('Cliente não encontrado.');
        }
    } else {
        die('ID do cliente não fornecido.');
    }
} else {

    try {
        // Consulta para buscar todas as categorias
        $sql = "SELECT categoria_id, titulo_categoria FROM Categorias";
        $stmt = $conexao->prepare(query: $sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao buscar categorias: " . $e->getMessage();
    }


    $prestador_id = $_SESSION['id'] ?? null;
    if ($prestador_id) {
        // Busca os dados do Prestador no banco de dados
        $sql = "SELECT * FROM Prestadores WHERE prestador_id = :prestador_id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':prestador_id', $prestador_id, PDO::PARAM_INT);
        $stmt->execute();

        // Obtém os dados do Prestador
        $prestador = $stmt->fetch(PDO::FETCH_ASSOC);
        $nomeSocialPreenchido = !empty($prestador['nome_social']);
        // Verifica se encontrou o Prestador
        if (!$prestador) {
            die('prestador não encontrado.');
        }
    } else {
        die('ID do prestador não fornecido.');
    }
}
?>

<body class="bodyCards">


    <?php ?>

    <!-- Verifique se existe uma mensagem de sucesso -->
    <?php if (isset($_SESSION['update_success'])): ?>
        <div id="success-alert" class="alert alert-success" role="alert">
            <?= $_SESSION['update_success']; ?>
        </div>
        <?php unset($_SESSION['update_success']); // Limpa a mensagem após exibir 
        ?>
    <?php endif; ?>

    <!-- Verifique se existe uma mensagem de erro -->
    <?php if (isset($_SESSION['update_error'])): ?>
        <div id="error-alert" class="alert alert-danger" role="alert">
            <?= $_SESSION['update_error']; ?>
        </div>
        <?php unset($_SESSION['update_error']); // Limpa a mensagem após exibir 
        ?>
    <?php endif; ?>


    <div class="container mt-4">
        <button type="button" id='meusAgendamentos' class="mb-2 btn btn-primary btn-servicos-contratados"
            style="background-color: #012640; color:white" onclick="window.location.href='../../index.php';">
            Voltar para Tela Inicial
        </button>

        <div class="row d-flex flex-wrap">
            <div class="col-md-4 mt-3">
                <div class="text-center area-foto-perfil mb-2">
                    <img id="fotoPerfil" src="../../assets/imgs/ruivo.png" alt="Ícone de usuário" class="foto-perfil" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="d-grid sidebar-menu">
                    <?php
                    if ($_SESSION['tipo_usuario'] === 'cliente') { ?>
                        <!-- PERFIL PRESTADOR -->
                        <button type="button" id='meusAgendamentos' class="mb-2 btn btn-primary btn-servicos-contratados"
                            style="background-color: #012640; color:white" onclick="window.location.href='../cliente/servicosContratados.php';">
                            Serviços Contratados
                        </button>
                        <button type="button" id='meusAgendamentos' class="mb-2 btn btn-primary btn-meus-agendamentos"
                            style="background-color: #012640; color:white" onclick="window.location.href='../cliente/agendamentosCliente.php'">
                            Meus Agendamentos
                        </button>
                    <?php } else { ?>
                        <button type="button" id='show-calendar' class="mb-2 mt-2 btn btn-primary btnVerificaDisponibilidade"
                            style="background-color: #012640; color:white" data-toggle="modal" data-target="#calendarModal">
                            Ajustar Agenda
                        </button>
                        <button type="button" id='btnAgendamentos' class="mb-2 btn btn-primary btnAgendamentos"
                            style="background-color: #012640; color:white" onclick="window.location.href='../prestador/agendamentosPendentes.php'">
                            Agendamentos pendentes
                        </button>
                        <button type="button" id='btnMeusProdutos' class="mb-2 btn btn-primary btn-meus-produtos"
                            style="background-color: #012640; color:white" onclick="window.location.href='../prestador/TelaMeusProdutos.php'">
                            Meus Serviços
                        </button>
                        <button type="button" id='MeusDestaques' class="mb-2 btn btn-primary btnMeusDestaques"
                            style="background-color: #012640; color:white" onclick="window.location.href='../prestador/destaquesPrestador.php'">
                            Meus Destaques
                        </button>
                    <?php } ?>

                    <!-- Botão comum pra todos os usuários -->
                    <button type="button" class="btn btn-primary mb-2" id="alterar-foto" style="background-color: #012640; color:white" data-bs-toggle="modal" data-bs-target="#modalAlterarFoto">
                        <i class="bi bi-pencil"></i> Alterar Foto
                    </button>
                    <button class="btn btn-primary edit-perfil mb-2" id="editarPerfil" style="background-color: #012640;">
                        <i class="bi bi-pencil"></i>Editar Perfil
                    </button>
                    <button type="button" class="btn btn-primary btnAlteraSenha mb-2" data-bs-toggle="modal" id="AlteraSenha" data-bs-target="#mdlAlteraSenha" style="background-color: #012640; color:white;">
                        <i class="bi bi-pencil"></i>Alterar Senha
                    </button>
                </div>

                <!-- Modal de Upload de Foto -->
                <div class="modal fade" id="modalAlterarFoto" tabindex="-1" aria-labelledby="modalAlterarFotoLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAlterarFotoLabel">Subir Nova Foto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formFotoPerfil">
                                    <div class="mb-3">
                                        <label for="inputFotoPerfil" class="form-label">Escolha uma nova foto de perfil</label>
                                        <input class="form-control" type="file" id="inputFotoPerfil" accept="image/*">
                                    </div>
                                    <div class="text-center">
                                        <img id="previewFotoPerfil" src="#" alt="Prévia da Foto" class="img-thumbnail" style="display:none; width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" style="background-color: #012640; color:white" id="salvarFoto">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mt-2">
                <form id="editForm" method="POST" action="../../backend/edita/editaPerfil.php">
                    <div class="row">
                        <!-- Nome completo -->
                        <?php if ($_SESSION['tipo_usuario'] == 'cliente' || $_SESSION['tipo_prestador'] == 'PF'): ?>
                            <div id="nomeCompleto" class="mb-3">
                                <label for="nome" class="form-label" id="nomeLabel">Nome Completo*</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['nome'] : $prestador['nome_resp_legal']; ?>" placeholder="Ex: João Antonio da Silva" disabled>
                                <div class="invalid-feedback"></div>
                            </div>
                        <?php endif; ?>

                        <!-- Nome resp legal -->
                        <?php if ($_SESSION['tipo_prestador'] == 'PJ'): ?>
                            <div class="mb-3" id="respLegal">
                                <label for="respLegal" class="form-label">Responsável Legal</label>
                                <input type="text" class="form-control" id="nome_resp_legal" name="nome_resp_legal" value="<?= $prestador['nome_resp_legal']; ?>" disabled>
                                <div class="invalid-feedback"></div>
                            </div>
                        <?php endif; ?>


                        <!-- Nome Social -->
                        <?php if ($_SESSION['tipo_usuario'] == 'cliente' || $_SESSION['tipo_prestador'] == 'PF'): ?>
                            <div class="form-check ms-2" style="display: none;">
                                <input class="form-check-input" type="checkbox" id="usarNomeSocialField"
                                    <?php if ($nomeSocialPreenchido): ?> checked <?php endif; ?>>
                                <label class="form-check-label mb-3" for="usarNomeSocialField">
                                    Desejo usar Nome Social
                                </label>
                            </div>
                            <?php if ($nomeSocialPreenchido): ?>
                                <div id="nomeSocialFields">
                                    <label for="nomeSocial" class="form-label">Nome Social *</label>
                                    <input type="text" class="form-control mb-3" id="nomeSocial" name="nomeSocial" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['nome_social'] : $prestador['nome_social']; ?>" disabled>
                                </div>
                            <?php else: ?>
                                <div id="nomeSocialFields" class="d-none">
                                    <label for="nomeSocial" class="form-label">Nome Social *</label>
                                    <input type="text" class="form-control mb-3" id="nomeSocial" name="nomeSocial" placeholder="Ex: Joãozinho" disabled>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Nome Fantasia -->
                        <?php if ($_SESSION['tipo_prestador'] == 'PJ'): ?>
                            <div class="mb-3" id="nomeFantasiaField">
                                <label for="nomeFantasia" class="form-label">Nome Fantasia *</label>
                                <input type="text" class="form-control" id="nomeFantasia" name="nomeFantasia" value="<?= $prestador['nome_fantasia']; ?>" disabled>
                            </div>
                        <?php endif; ?>

                        <!-- Razão Social -->
                        <?php if ($_SESSION['tipo_prestador'] == 'PJ'): ?>
                            <div class="mb-3" id="razaoSocialField">
                                <label for="razaoSocial" class="form-label">Razão Social *</label>
                                <input type="text" class="form-control" id="razaoSocial" name="razaoSocial" value="<?= $prestador['razao_social']; ?>" disabled>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-7 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['email'] : $prestador['email']; ?>" disabled> <!-- usado if ternario -->
                                <div class="emailFeedback"></div>
                            </div>
                            <!-- Data de Nascimento -->
                            <?php if ($_SESSION['tipo_prestador'] != 'PJ'): ?>
                                <div class="col-md-5 mb-3" id="dataNascimentoFields">
                                    <label for="dataNascimento" class="form-label">Data de Nascimento *</label>
                                    <input type="date" class="form-control text-center" id="dataNascimento" name="dataNascimento" value="<?= ($_SESSION['tipo_usuario'] === 'cliente') ? $cliente['data_nascimento'] : $prestador['data_nascimento']; ?>" disabled>
                                    <div class="invalid-feedback">Por favor, insira uma data acima de 1924 e abaixo de 2124.</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <!-- CNPJ -->
                            <?php if ($_SESSION['tipo_prestador'] === 'PJ'): ?>
                                <div class="col-md-6 mb-3" id="cnpjFields" class="d-none">
                                    <label for="cnpj" class="form-label">CNPJ *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="18" value="<?= $prestador['cnpj']; ?>" disabled>
                                    <div class="invalid-feedback">Por favor, preencha um CNPJ válido.</div>
                                </div>
                            <?php endif; ?>
                            <!-- CPF -->
                            <?php if ($_SESSION['tipo_prestador'] === 'PF' || $_SESSION['tipo_usuario'] === 'cliente'): ?>
                                <div class="col-md-6 mb-3" id="cpfFields" class="d-none">
                                    <label for="cpf" class="form-label">CPF *</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['cpf'] : $prestador['cpf']; ?>" disabled>
                                    <div class="invalid-feedback">Por favor, preencha um CPF válido.</div>
                                </div>
                            <?php endif; ?>

                            <!-- Categoria -->
                            <?php if ($_SESSION['tipo_usuario'] == 'prestador'): ?>
                                <div class="col-md-6 mb-3" id="categoriaFields">
                                    <label for="categoria" class="form-label">Categoria *</label>
                                    <select class="form-select" id="categoria" name="categoria" disabled>
                                        <option value="">Selecione uma categoria</option>
                                        <?php
                                        if (!empty($categorias)) {
                                            // Obtém a categoria selecionada do prestador (se houver)
                                            $categoriaSelecionada = (int) $prestador['categoria'];

                                            foreach ($categorias as $categoria) {
                                                // Converte o valor de 'categoria_id' para int para garantir a comparação correta
                                                $categoriaId = (int) $categoria['categoria_id'];

                                                // Verifica se a categoria atual é a que está selecionada no banco de dados
                                                $selected = ($categoriaId === $categoriaSelecionada) ? 'selected' : '';

                                                // Exibe a opção com o atributo 'selected' para a categoria marcada
                                                echo '<option value="' . $categoria['categoria_id'] . '" ' . $selected . '>' . htmlspecialchars($categoria['titulo_categoria'], ENT_QUOTES, 'UTF-8') . '</option>';
                                            }
                                        } else {
                                            echo '<option value="" disabled>Nenhuma categoria disponível</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Descricão -->
                        <?php if ($_SESSION['tipo_usuario'] === 'prestador'): ?>
                            <div id="descricaoFields">
                                <div class="mb-3">
                                    <label for="descricao" class="form-label">Descrição do Negócio *</label>
                                    <textarea class="form-control descricaoNegocio" id="descricao" name="descricao" disabled><?= htmlspecialchars($prestador['descricao']); ?></textarea>
                                    <div class="invalid-feedback" id="descricao-error">A descrição deve ter pelo menos 30 caracteres.</div>
                                    <small id="charCount" class="form-text text-muted">0 caracteres</small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Celular  -->
                        <div class="col-md-6 mb-3">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="tel" class="form-control" id="celular" name="celular" pattern="\(\d{2}\) \d{5}-\d{4}" aria-required="true" maxlength="15" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['celular'] : $prestador['celular']; ?>" disabled>
                            <div id="aviso-celular" class="text-danger" style="display:none;"></div>
                        </div>

                        <!-- Telefone -->
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="telefone" name="telefone" pattern="\(\d{2}\) \d{4}-\d{4}" maxlength="14" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['telefone'] : $prestador['telefone']; ?>" disabled>
                            <div id="aviso-telefone" class="text-danger" style="display:none;"></div>
                        </div>

                        <!-- CEP -->
                        <div class="col-md-6 mb-3">
                            <label for="cep" class="form-label">CEP *</label>
                            <input type="text" class="form-control" id="cep" name="cep" pattern="\d{5}-\d{3}" aria-required="true" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['cep'] : $prestador['cep']; ?>" disabled>
                            <small id="cepHelp" class="form-text text-muted">
                                <a href="https://buscacepinter.correios.com.br/app/endereco/index.php" id="buscarCep" target="_blank" style="text-decoration: none;">Não sei meu CEP</a>
                            </small>
                        </div>

                        <div class="row">
                            <!-- Endereço -->
                            <div class="col-md-5 mb-3">
                                <label for="endereco" class="form-label">Endereço *</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['logradouro'] : $prestador['logradouro']; ?>" disabled>
                            </div>
                            <!-- Bairro -->
                            <div class="col-md-4 mb-3">
                                <label for="bairro" class="form-label">Bairro *</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['bairro'] : $prestador['bairro']; ?>" disabled>
                            </div>
                            <!-- Número -->
                            <div class="col-md-3 mb-3">
                                <label for="numero" class="form-label">Número *</label>
                                <input type="number" class="form-control numero-menor" id="numero" name="numero" maxlength="8" min="0" step="1" oninput="this.value = this.value.slice(0, 8)" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['numero'] : $prestador['numero']; ?>" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Cidade -->
                            <div class="col-md-4 mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['cidade'] : $prestador['cidade']; ?>" disabled>
                            </div>
                            <!-- Uf -->
                            <div class="col-md-4 mb-3">
                                <label for="uf" class="form-label">Uf</label>
                                <input type="text" class="form-control" id="uf" name="uf" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['uf'] : $prestador['uf']; ?>" disabled>
                            </div>
                            <!-- Complemento -->
                            <div class="col-md-4 mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= ($_SESSION['tipo_usuario'] == 'cliente') ? $cliente['complemento'] : $prestador['complemento']; ?>" disabled>
                            </div>
                        </div>

                        <!-- Botoes de salvar e cancelar -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                            <button type="submit" class="btn btn-primary mb-2" style="background-color: #012640; color:white; display:none;" \>Salvar</button>
                            <button type="button" id="cancelarEdicao" class="btn btn-secondary mb-2" style="display:none;">Cancelar</button>
                        </div>
                    </div>

                    <div class="modal fade" id="mdlAlteraSenha" tabindex="-1" aria-labelledby="mdlAlteraSenhaLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="senhaAtual">Senha atual</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="senhaAtual" style="background-color: white; border: 1px solid #1A3C53; border-radius: 5px;">
                                                <button type="button" class="btn" id="toggleSenhaAtual" style="color: #1A3C53; ">
                                                    <i class="bi bi-eye-slash" id="iconSenhaAtual" style="color: #1A3C53;"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="novaSenha">Nova Senha</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="novaSenha" style="background-color: white; border: 1px solid #1A3C53; border-radius: 5px;">
                                                <button type="button" class="btn" id="toggleNovaSenha" style="color: #1A3C53;">
                                                    <i class="bi bi-eye-slash" id="iconNovaSenha" style="color: #1A3C53;"></i>
                                                </button>
                                            </div>
                                            <small id="passwordHelpBlock" class="form-text text-muted">
                                                Sua senha deve ter entre 8 e 20 caracteres.
                                            </small>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer alteraSenhaFooter">
                                    <button type="submit" class="btn btn-primary mb-2"
                                        style="background-color: #012640; color:white">Confirmar Senha</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    include '../layouts/footer.php';
    ?>
    <script src="../../assets/js/validaCamposGlobal.js"></script>
    <script src="../../assets/js/editaPerfil.js"></script>

</body>

</html>