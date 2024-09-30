<?php

// Connection details
$hostname = '108.179.193.15';
$username = 'axeyfu72_root';
$password = 'AiOu}v3P0kx6';
$database = 'axeyfu72_db';

// Create a connection to the database
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a new category
function createCategory($conn) {
    if (isset($_POST['create_category'])) {
        $titulo_categoria = trim($_POST['titulo_categoria']);
        $descricao_categoria = trim($_POST['descricao_categoria']);

        if (empty($titulo_categoria) || empty($descricao_categoria) || ctype_space($titulo_categoria) || ctype_space($descricao_categoria)) {
            $erro = "Erro: Não é possível criar uma categoria vazia ou nulla. Por favor, preencha todos os campos com texto válido.";
        } else {
            $sql = "INSERT INTO Categorias (titulo_categoria, descricao_categoria) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $titulo_categoria, $descricao_categoria);
            $stmt->execute();

            ob_end_flush();
            header("Refresh:0");
            exit;
        }
    }
}

// Function to update an existing category
function updateCategory($conn) {
    if (isset($_POST['update_category'])) {
        $categoria_id = $_POST['categoria_id'];
        $titulo_categoria = trim($_POST['titulo_categoria']);
        $descricao_categoria = trim($_POST['descricao_categoria']);

        if (empty($titulo_categoria) || empty($descricao_categoria) || ctype_space($titulo_categoria) || ctype_space($descricao_categoria)) {
            $erro = "Erro: Não é possível atualizar uma categoria vazia ou nulla. Por favor, preencha todos os campos com texto válido.";
        } else {
            $sql = "UPDATE Categorias SET titulo_categoria=?, descricao_categoria=? WHERE categoria_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $titulo_categoria, $descricao_categoria, $categoria_id);
            $stmt->execute();

            ob_end_flush();
            header("Refresh:0");
            exit;
        }
    }
}

// Function to delete a category
function deleteCategory($conn) {
    if (isset($_POST['delete_category'])) {
        $categoria_id = $_POST['categoria_id'];

        $sql = "DELETE FROM Categorias WHERE categoria_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();

        ob_end_flush();
        header("Refresh:0");
        exit;
    }
}

// Function to retrieve all categories
function getAllCategories($conn) {
    $sql = "SELECT * FROM Categorias";
    $result = $conn->query($sql);
    return $result;
}

// Function to retrieve a single category by its ID
function getCategoryById($conn, $categoria_id) {
    $sql = "SELECT * FROM Categorias WHERE categoria_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Handle form submissions
createCategory($conn);
updateCategory($conn);
deleteCategory($conn);

// Retrieve all categories
$categories = getAllCategories($conn);

// Close the database connection
$conn->close();