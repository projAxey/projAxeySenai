<?php
include '../../padroes/head.php';
include '../../padroes/nav.php';
?>

<body class="bodyCards">
    
    <div class="container mt-4">                   
        <button type="button" id='meusAgendamentos' class="mb-2 btn btn-primary btn-servicos-contratados"
            style="background-color: #012640; color:white" onclick="window.location.href='../../index.php';">
            Voltar para Tela Inicial
        </button>
        <!-- <a href="perfilCliente.php" style="text-decoration: none; color:#012640;"><strong>Voltar a página principal</strong></a> -->
        
        <div class="row d-flex flex-wrap">
            <div class="col-md-4 mt-2">
                <div class="text-center area-foto-perfil">                    
                    <img id="fotoPerfil" src="../../assets/imgs/ruivo.png" alt="Ícone de usuário" class=" foto-perfil" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="d-grid sidebar-menu">
                    <button type="button" class="btn btn-primary mb-2 mt-2" id="alterar-foto" style="background-color: #012640; color:white"data-bs-toggle="modal" data-bs-target="#modalAlterarFoto">
                        <i class="bi bi-pencil"></i> Alterar Foto
                    </button>
                    <button class="btn btn-primary edit-perfil mb-2" id="editarPerfil"style="background-color: #012640;"><i class="bi bi-pencil">
                    </i>Editar Perfil</button>
                    <button type="button" class="btn btn-primary btnAlteraSenha mb-2" data-bs-toggle="modal" id="AlteraSenha" data-bs-target="#mdlAlteraSenha"style="background-color: #012640; color:white;"><i class="bi bi-pencil">
                    </i>Alterar Senha</button>
                    <button type="button" id='meusAgendamentos' class="mb-2 btn btn-primary btn-servicos-contratados"
                        style="background-color: #012640; color:white" onclick="window.location.href='servicosContratados.php';">
                        Serviços Contratados 
                    </button>
                    <button type="button" id='meusAgendamentos' class="mb-2 btn btn-primary btn-meus-agendamentos"
                        style="background-color: #012640; color:white" onclick="window.location.href='agendamentosCliente.php'" >
                        Meus Agendamentos 
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
                <form class="mt-3" id="editForm">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" maxlength="100" required
                                aria-required="true" disabled>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="nome-social-checkbox">
                                <label class="form-check-label" for="nome-social-checkbox" >Usar Nome Social</label>
                            </div>
                        </div>

                        <div class="col-md-12" id="nome-social-field" style="display: none;">
                            <label for="nome-social" class="form-label">Nome Social</label>
                            <input type="text" class="form-control" id="nome-social" maxlength="100">
                        </div>

                        <div class="col-md-8">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required aria-required="true"
                                maxlength="100">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="tel" class="form-control" id="celular" pattern="\(\d{2}\) \d{5}-\d{4}"
                                placeholder="(XX) XXXXX-XXXX" required aria-required="true" maxlength="15">
                        </div>
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="telefone" pattern="\(\d{2}\) \d{4}-\d{4}"
                                placeholder="(XX) XXXX-XXXX" maxlength="14">
                        </div>
                        <div class="col-md-6">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" pattern="\d{5}-\d{3}"
                                placeholder="XXXXX-XXX" required aria-required="true">
                            <small id="cepHelp" class="form-text text-muted">
                                <a href="https://buscacepinter.correios.com.br/app/endereco/index.php" id="buscarCep"
                                    target="_blank" style=" text-decoration: none;
                                    color: #012640;">Não sei meu Cep</a>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade">
                        </div>
                        <div class="col-md-6">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro">
                        </div>
                        <div class="col-md-6">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco">
                        </div>
                        <div class="col-md-6">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" maxlength="10">
                        </div>
                        <div class="col-md-6">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" maxlength="25">
                        </div>

                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <button type="submit" class="btn btn-primary mb-2"
                            style="background-color: #012640; color:white">Salvar</button>
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
                                        <button type="button" class="btn" id="toggleSenhaAtual" style = "color: #1A3C53; ">
                                            <i class="bi bi-eye-slash" id="iconSenhaAtual" style="color: #1A3C53;"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="novaSenha">Nova Senha</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="novaSenha" style="background-color: white; border: 1px solid #1A3C53; border-radius: 5px;">
                                        <button type="button" class="btn" id="toggleNovaSenha" style = "color: #1A3C53;">
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
    <?php include '../../padroes/footer.php'; ?>
    <script src="../../assets/JS/global.js"></script>
</body>

</html>