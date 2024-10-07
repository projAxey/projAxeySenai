<?php
session_start();
require_once '../../config/conexao.php'; // Conexão com o banco de dados

$id = $_SESSION['id'];
$tipoUsuario = $_SESSION['tipo_usuario'];

// Captura os dados dos usuários 
$dados = [
    'nome' => $_POST['nome'] ?? null,
    'nome_resp_legal' => $_POST['nome_resp_legal'] ?? null,
    'nomeSocial' => $_POST['nomeSocial'] ?? null,
    'nomeFantasia' => $_POST['nomeFantasia'] ?? null,
    'razaoSocial' => $_POST['razaoSocial'] ?? null,
    'email' => $_POST['email'] ?? null,
    'dataNascimento' => $_POST['dataNascimento'] ?? null,
    'cnpj' => $_POST['cnpj'] ?? null,
    'cpf' => $_POST['cpf'] ?? null,
    'categoria' => $_POST['categoria'] ?? null,
    'descricao' => $_POST['descricao'] ?? null,
    'celular' => $_POST['celular'] ?? null,
    'telefone' => $_POST['telefone'] ?? null,
    'cep' => $_POST['cep'] ?? null,
    'logradouro' => $_POST['endereco'] ?? null,
    'bairro' => $_POST['bairro'] ?? null,
    'numero' => $_POST['numero'] ?? null,
    'cidade' => $_POST['cidade'] ?? null,
    'uf' => $_POST['uf'] ?? null,
    'complemento' => $_POST['complemento'] ?? null,
];

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../frontend/auth/login.php");
    exit();
}

function atualizarDados($conexao, $tipoUsuario, $dados, $id) {
    if ($tipoUsuario == 'cliente') {
        $sql = "UPDATE Clientes SET 
                nome = :nome, 
                nome_social = :nomeSocial, 
                email = :email, 
                data_nascimento = :dataNascimento, 
                cpf = :cpf, 
                celular = :celular, 
                telefone = :telefone, 
                cep = :cep, 
                logradouro = :logradouro, 
                bairro = :bairro, 
                numero = :numero, 
                cidade = :cidade, 
                uf = :uf, 
                complemento = :complemento 
                WHERE cliente_id = :cliente_id";

        $params = [
            ':nome' => $dados['nome'],
            ':nomeSocial' => $dados['nomeSocial'],
            ':email' => $dados['email'],
            ':dataNascimento' => $dados['dataNascimento'],
            ':cpf' => $dados['cpf'],
            ':celular' => $dados['celular'],
            ':telefone' => $dados['telefone'],
            ':cep' => $dados['cep'],
            ':logradouro' => $dados['logradouro'],
            ':bairro' => $dados['bairro'],
            ':numero' => $dados['numero'],
            ':cidade' => $dados['cidade'],
            ':uf' => $dados['uf'],
            ':complemento' => $dados['complemento'],
            ':cliente_id' => $id,
        ];
    } else if ($tipoUsuario == 'prestador') {
        $sql = "UPDATE Prestadores SET
                nome_resp_legal = :nome_resp_legal,
                nome_social = :nomeSocial, 
                nome_fantasia = :nomeFantasia, 
                razao_social = :razaoSocial, 
                email = :email, 
                data_nascimento = :dataNascimento, 
                cnpj = :cnpj, 
                cpf = :cpf, 
                categoria = :categoria, 
                descricao = :descricao, 
                celular = :celular, 
                telefone = :telefone, 
                cep = :cep, 
                logradouro = :logradouro, 
                bairro = :bairro, 
                numero = :numero, 
                cidade = :cidade, 
                uf = :uf 
                WHERE prestador_id = :prestador_id";

        $params = [
            ':nome_resp_legal' => $dados['nome_resp_legal'],
            ':nomeSocial' => $dados['nomeSocial'],
            ':nomeFantasia' => $dados['nomeFantasia'],
            ':razaoSocial' => $dados['razaoSocial'],
            ':email' => $dados['email'],
            ':dataNascimento' => $dados['dataNascimento'],
            ':cnpj' => $dados['cnpj'],
            ':cpf' => $dados['cpf'],
            ':categoria' => $dados['categoria'],
            ':descricao' => $dados['descricao'],
            ':celular' => $dados['celular'],
            ':telefone' => $dados['telefone'],
            ':cep' => $dados['cep'],
            ':logradouro' => $dados['logradouro'],
            ':bairro' => $dados['bairro'],
            ':numero' => $dados['numero'],
            ':cidade' => $dados['cidade'],
            ':uf' => $dados['uf'],
            ':prestador_id' => $id,
        ];
    }

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            $_SESSION['update_success'] = 'Dados atualizados com sucesso!';
        } else {
            $_SESSION['update_error'] = 'Nenhuma alteração foi feita.';
        }
    } catch (Exception $e) {
        $_SESSION['update_error'] = 'Erro ao atualizar dados: ' . $e->getMessage();
    }
}

// Chama a função para atualizar os dados
atualizarDados($conexao, $tipoUsuario, $dados, $id);

header("Location: /projAxeySenai/frontend/auth/perfil.php"); // Redirecionar após o sucesso
exit();