<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variáveis
$cliente = null;

// Se o formulário for enviado, busca o usuário pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_cliente'])) {
        $busca = trim($_POST['busca_cliente']);

        // Verifica se a busca é um número (ID) ou um nome
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM cliente WHERE nome LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o cliente não for encontrado, exibe um alerta
        if (!$cliente) {
            echo "<script>alert('Cliente não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Íris &ssence - Beauty Clinic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="script.js"></script>
    <link rel="icon" href="../imgs/logo.jpg" type="image/x-icon">
</head>
<body class="cadastro-fundo">
    <header>
        <nav>
            <ul>
                <a href="../html/index.html">
                  <img src="../imgs/logo.jpg" class="logo" alt="Logo">
                </a>
                <li><a href="../html/index.html">HOME</a></li>
                <li>
                    <a href="#">PROCEDIMENTOS FACIAIS</a>
                    <div class="submenu">
                        <a href="../html/limpezapele.html">Limpeza de Pele</a>
                        <a href="../html/peelingquimico.html">Peeling Químico</a>
                        <a href="../html/microagulhamento.html">Microagulhamento</a>
                        <a href="../html/rejuvenescimento.html">Rejuvenescimento</a>
                        <a href="../html/acne.html">Tratamento para Acne</a>
                    </div>
                </li>
                <li>
                    <a href="#">PROCEDIMENTOS CORPORAIS</a>
                    <div class="submenu">
                        <a href="../html/massagemmodeladora.html">Massagem Modeladora</a>
                        <a href="../html/drenagemlinfatica.html">Drenagem Linfática</a>
                        <a href="../html/radiofrequencia.html">Radiofrequência</a>
                        <a href="../html/criolipolise.html">Criolipólise</a>
                        <a href="../html/depilacaolaser.html">Depilação a Laser</a>
                    </div>
                </li>

                <li><a href="../html/produtos.html">PRODUTOS</a></li>
                  
                |<li><a href="../html/agendamento.html">AGENDAR</a></li>|
                <li><a href="../html/login.html">LOGIN</a></li>|
                <li><a href="../html/cadastro.html">CADASTRO</a></li>|
            </ul>
        </nav>
    </header>
    <br><br><br><br><br>

    <div class="formulario">
    <fieldset>

    <!-- Formulário para buscar usuário pelo ID ou Nome -->
    <form action="alterar_cliente.php" method="POST">
        <legend>Alterar usuário</legend>
        <label for="busca_cliente">Digite o ID ou Nome do usuário:</label>
        <input type="text" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestoes()">
        
        <!-- Div para exibir sugestões de usuários -->
        <div id="sugestoes"></div>
        
        <button class="botao_cadastro" type="submit">Buscar</button>
    </form>

    <?php if ($cliente): ?>
        <!-- Formulário para alterar usuário -->
        <form action="processa_alteracao_usuario.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="text" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($cliente['data_nascimento']) ?>" required>

            <label for="genero">Genero:</label>
            <input type="text" id="genero" name="genero" value="<?= htmlspecialchars($cliente['genero']) ?>" required>

            <label for="id_perfil">Perfil:</label>
            <select id="id_perfil" name="id_perfil">
                <option value="1" <?= $cliente['id_perfil'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $cliente['id_perfil'] == 2 ? 'selected' : '' ?>>Recepcionista</option>
                <option value="3" <?= $cliente['id_perfil'] == 3 ? 'selected' : '' ?>>Cliente</option>
            </select>

            <!-- Se o usuário logado for ADM, exibir opção de alterar senha -->
            <?php if ($_SESSION['perfil'] == 1): ?>
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha">
            <?php endif; ?>

            <div class="botoes">
            <button class="botao_cadastro" type="submit">Alterar</button>
            <button class="botao_limpeza" type="reset">Cancelar</button>
            </div>

            <a href="principal.php">Voltar</a>
        
            </form>
            </fieldset>
        </div>
    <?php endif; ?>
    <br><br><br><br><br><br>
    <footer class="l-footer">&copy; 2025 Iris Essence - Beauty Clinic. Todos os direitos reservados.</footer>
</body>
</html>
