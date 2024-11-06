<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tarefas</title>
    <link rel="stylesheet" href="stye.css"> <!-- Corrigido o nome do arquivo CSS -->
</head>
<body>
    <header>
        <h1>Cadastro de Tarefas</h1>
        <nav>
            <ul>
                <li><a href="index.php">Página Principal</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form action="" method="post">
            <label for="titulo">Título da Tarefa:</label><br/>
            <input type="text" id="titulo" name="titulo" required><br/>
            <label for="descricao">Descrição:</label><br/>
            <textarea id="descricao" name="descricao" required></textarea><br/><br/>

            <!-- Botão para Inserir -->
            <input type="submit" name="acao" value="Inserir">
        </form>

        <?php
        // Configurações do banco de dados
        $host = 'localhost'; 
        $db = 'db_gereciamentos'; 
        $user = 'usuarios'; 

        // Conexão com o banco de dados
        try {
            $conn = new PDO("mysql:host=$host;dbname=$db", $user);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }

        // Processamento do formulário
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $acao = $_POST['acao'];
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $setor = $_POST['setor'] ?? '';
            if ($acao === 'Inserir') {
                // Lógica para inserir a tarefa no banco de dados
                try {
                    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao) VALUES (:titulo, :descricao)");
                    $stmt->bindParam(':titulo', $titulo);
                    $stmt->bindParam(':descricao', $descricao);
                    $stmt->bindParam(':setor', $setor);
                    if ($stmt->execute()) {
                        echo "<p>Tarefa '$titulo' cadastrada com sucesso!</p>";
                    } else {
                        echo "<p>Erro ao cadastrar a tarefa.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Erro ao executar a consulta: " . $e->getMessage() . "</p>";
                }
            }
        }
        ?>
    </main>
</body>
</html>