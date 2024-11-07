<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="style.css"> <!-- Corrigido aqui -->
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
        // Iniciar sessão para armazenar as tarefas
        session_start();

        // Se a sessão de tarefas não existir, inicializá-la como um array vazio
        if (!isset($_SESSION['tarefas'])) {
            $_SESSION['tarefas'] = [];
        }

        // Processar a ação de inserir
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $acao = $_POST['acao'];
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';

            if ($acao === 'Inserir') {
                // Adicionar o usuário à sessão
                $_SESSION['tarefas'][] = ['nome' => $nome, 'email' => $email];
                echo "<p>Usuário '$nome' cadastrado com sucesso!</p>";
            } else {
                echo "<p>Ação não reconhecida.</p>";
            }
        }

        // Exibir a lista de usuários cadastrados
        if (count($_SESSION['tarefas']) > 0) {
            echo "<h2>Usuários Cadastrados:</h2>";
            echo "<ul>";
            foreach ($_SESSION['tarefas'] as $tarefa) {
                echo "<li><strong>" . htmlspecialchars($tarefa['nome']) . "</strong> - " . htmlspecialchars($tarefa['email']) . "</li>";
            }
            echo "</ul>";
        }
        ?>
    </main>
</body>
</html>
