<?php
require 'conexao.php'; // inclui seu arquivo de conexão, que define $pdo

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);
    $nome = isset($_POST["nome"]) ? htmlspecialchars(trim($_POST["nome"])) : "";
    $senha = isset($_POST["senha"]) ? htmlspecialchars(trim($_POST["senha"])) : "";
    $id_perfil = isset($_POST["id_perfil"]) ? htmlspecialchars(trim($_POST["id_perfil"])) : "";
    $email = isset($_POST["email"]) ? filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) : false;

    if (!$id || !$email) {
        die("Erro: ID inválido ou e-mail incorreto.");
    }

    $sql = "UPDATE usuario SET nome = :nome, senha = :senha, id_perfil = :id_perfil, email = :email WHERE id_usuario = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":senha", $senha);
    $stmt->bindParam(":id_perfil", $id_perfil);
    $stmt->bindParam(":email", $email);

    try {
        $stmt->execute();
        echo "Usuário atualizado com sucesso!";
    } catch (PDOException $e) {
        error_log("Erro ao atualizar usuário: " . $e->getMessage());
        echo "Erro ao atualizar registro.";
    }
}
?>

