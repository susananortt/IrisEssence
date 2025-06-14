<?php
    session_start();
    require_once 'conexao.php';

    //GARANTE QUE O USUARIO ESTEJA LOGADO
    if(!isset($_SESSION['usuario'])){
        header("Location: login.php");
        exit();
    }

        //OBTENDO O NOME DO PERFIL DO USUARIO LOGADO

        $id_perfil = $_SESSION['perfil'];
        $sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
        $stmtPerfil = $pdo->prepare($sqlPerfil);
        $stmtPerfil->bindParam(':id_perfil', $id_perfil);
        $stmtPerfil->execute();
        $perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
        $nome_perfil = $perfil['nome_perfil'];

        //DEFINIÇÃO DAS PERMISSÕES POR PERFIL

        $permissoes = [
            1 => ["Cadastrar" =>[
            "cadastro_usuario.php",
            "cadastro_perfil.php", 
            "cadastro_cliente.php",
            "cadastro_fornecedor.php",
            "cadastro_produto.php",
            "cadastro_funcionario.php" ],

            "Buscar" =>[
            "buscar_usuario.php",
            "buscar_perfil.php", 
            "buscar_cliente.php",
            "buscar_fornecedor.php",
            "buscar_produto.php",
            "buscar_funcionario.php" ],

            "Alterar" =>[
            "alterar_usuario.php",
            "alterar_perfil.php", 
            "alterar_cliente.php",
            "alterar_fornecedor.php",
            "alterar_produto.php",
            "alterar_funcionario.php" ],

            "Excluir" =>[
            "excluir_usuario.php",
            "excluir_perfil.php", 
            "excluir_cliente.php",
            "excluir_fornecedor.php",
            "excluir_produto.php",
            "excluir_funcionario.php" ]],

            2 => ["Cadastrar" =>[ 
            "cadastro_cliente.php" ],

            "Buscar" =>[
            "buscar_cliente.php",
            "buscar_fornecedor.php",
            "buscar_produto.php"],

            "Alterar" =>[
            "alterar_cliente.php",
            "alterar_fornecedor.php"]],

            3 => ["Cadastrar" =>[
            "cadastro_cliente.php" ],

            "Buscar" =>[
            "buscar_produto.php"],

            "Alterar" =>[
            "alterar_cliente.php"]]

        ];
    
        //OBTENDO AS OPÇÕES DISPONIVEIS PARA O PERFIL LOGADO.

        $opcoes_menu = $permissoes[$id_perfil];  
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
                <li><a href="../html/logout.html">LOGOUT</a></li>|
                <li><a href="../html/cadastro.html">CADASTRO</a></li>|
            </ul>
        </nav>


        <div class="saudacao">
            <h2>Bem Vindo, <?php echo $_SESSION["usuario"];?>! 
            Perfil: <?php echo $nome_perfil; ?></h2>
        </div>

        <div class="logout">
            <form action = "logout.php" method= "POST">
                <button type="submit">Logout</button>
        </div>
    </header>

    <nav>
        <ul class = "menu">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href = "#"><?= $categoria ?></a>
                    <ul class = "dropdown-menu">
                        <?php foreach($arquivos as $arquivo): ?>
                            <li>
                                <a href = "<?= $arquivo ?>"><?= ucfirst(str_replace("_"," ", basename($arquivo, ".php")))?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="l-footer">&copy; 2025 Iris Essence - Beauty Clinic. Todos os direitos reservados.</footer>
</body>
</html>