<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="stye.css"> <!-- Corrigido aqui -->
</head>
<body>
    <header>
        <h1>Cadastro de Usuários</h1>
        <nav>
            <ul>
                <li><a href="index.php">Página Principal</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form action="" method="post">
            <label for="nome">Nome:</label><br/>
            <input type="text" id="nome" name="nome" required><br/>
            <label for="email">Email:</label><br/>
            <input type="email" id="email" name="email" required><br/><br/>
            
            <!-- Botão para ação de Inserir -->
            <input type="submit" name="acao" value="Inserir">
        </form>

        <?php
        // Aqui você pode processar a ação com base no botão pressionado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $acao = $_POST['acao'];
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';

            if ($acao === 'Inserir') {
                // Lógica para inserir o usuário no banco de dados
                echo "<p>Usuário '$nome' cadastrado com sucesso!</p>";
            } else {
                echo "<p>Ação não reconhecida.</p>";
            }
        }
        ?>
    </main>
</body>
</html>