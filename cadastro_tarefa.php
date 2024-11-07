<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tarefas</title>
    <link rel="stylesheet" href="style.css">
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
        <form action="" method="post" id="formTarefa">
            <label for="titulo">Título da Tarefa:</label><br />
            <input type="text" id="titulo" name="titulo" required><br />
            <label for="descricao">Descrição:</label><br />
            <textarea id="descricao" name="descricao" required></textarea><br /><br />

            <input type="submit" name="acao" value="Inserir">
        </form>

        <?php
        // Configurações do banco de dados
        $host = 'localhost';
        $dbname = 'db_gereciamentos';
        $username = 'root';

        // Conexão com o banco de dados
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }

        // Processamento do formulário
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $acao = $_POST['acao'];
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            if ($acao === 'Inserir') {
                try {
                    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao) VALUES (:titulo, :descricao)");
                    $stmt->bindParam(':titulo', $titulo);
                    $stmt->bindParam(':descricao', $descricao);

                    if ($stmt->execute()) {
                        echo "<p class='success'>Tarefa '$titulo' cadastrada com sucesso!</p>";
                    } else {
                        echo "<p class='error'>Erro ao cadastrar a tarefa.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='error'>Erro ao executar a consulta: " . $e->getMessage() . "</p>";
                }
            }
        }

        // Exibir tarefas cadastradas
        try {
            $stmt = $conn->query("SELECT * FROM tarefas ORDER BY id DESC");
            $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($tarefas) {
                echo "<h2>Tarefas Cadastradas:</h2>";
                echo "<ul>";
                foreach ($tarefas as $tarefa) {
                    echo "<li><strong>" . htmlspecialchars($tarefa['titulo']) . "</strong>: " . htmlspecialchars($tarefa['descricao']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Nenhuma tarefa cadastrada ainda.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Erro ao buscar tarefas: " . $e->getMessage() . "</p>";
        }
        ?>
    </main>
</body>

</html>
