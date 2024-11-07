<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="style.css"> <!-- Corrigido o nome do arquivo CSS -->
</head>
<body>
    <header>
        <h1>Gerenciamento de Tarefas</h1>
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

            <label for="status">Status da Tarefa:</label><br/>
            <select id="status" name="status" required>
                <option value="Em andamento">Em andamento</option>
                <option value="Concluída">Concluída</option>
                <option value="Pendente">Pendente</option>
            </select><br/><br/>

            <!-- Botões para ações -->
            <input type="submit" name="acao" value="Inserir">
            <input type="submit" name="acao" value="Alterar">
            <input type="submit" name="acao" value="Excluir">
            <input type="submit" name="acao" value="Consultar">
        </form>

        <?php
        // Configurações do banco de dados
        $host = 'localhost'; 
        $dbname = 'db_gereciamentos'; // Correção para o nome correto da variável
        $username = 'root'; // Usuário root
        $password = ''; // Sem senha, conforme solicitado

        try {
            // Conexão com o banco de dados usando PDO
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            die(); // Para a execução do código em caso de falha na conexão
        }

        // Criar a tabela tarefas caso não exista
        $createTableSQL = "CREATE TABLE IF NOT EXISTS tarefas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descricao TEXT NOT NULL,
            status ENUM('Em andamento', 'Concluída', 'Pendente') NOT NULL
        )";

        // Executa o SQL para criar a tabela
        try {
            $conn->exec($createTableSQL);
        } catch (PDOException $e) {
            echo "Erro ao criar a tabela: " . $e->getMessage();
        }

        // Processamento do formulário
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $acao = $_POST['acao'];
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $status = $_POST['status'] ?? '';
            $id = $_POST['id'] ?? null;

            switch ($acao) {
                case 'Inserir':
                    // Lógica para inserir a tarefa no banco de dados
                    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, status) VALUES (:titulo, :descricao, :status)");
                    $stmt->bindParam(':titulo', $titulo);
                    $stmt->bindParam(':descricao', $descricao);
                    $stmt->bindParam(':status', $status);
                    
                    if ($stmt->execute()) {
                        echo "<p>Tarefa '$titulo' cadastrada com sucesso!</p>";
                    } else {
                        echo "<p>Erro ao cadastrar a tarefa.</p>";
                    }
                    break;

                case 'Alterar':
                    // Lógica para alterar a tarefa no banco de dados
                    if ($id) {
                        $stmt = $conn->prepare("UPDATE tarefas SET titulo = :titulo, descricao = :descricao, status = :status WHERE id = :id");
                        $stmt->bindParam(':titulo', $titulo);
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':status', $status);
                        $stmt->bindParam(':id', $id);
                        
                        if ($stmt->execute()) {
                            echo "<p>Tarefa com ID '$id' alterada com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao alterar a tarefa.</p>";
                        }
                    } else {
                        echo "<p>Por favor, forneça um ID para alterar.</p>";
                    }
                    break;

                case 'Excluir':
                    // Lógica para excluir a tarefa do banco de dados
                    if ($id) {
                        $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = :id");
                        $stmt->bindParam(':id', $id);
                        
                        if ($stmt->execute()) {
                            echo "<p>Tarefa com ID '$id' excluída com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao excluir a tarefa.</p>";
                        }
                    } else {
                        echo "<p>Por favor, forneça um ID para excluir.</p>";
                    }
                    break;

                case 'Consultar':
                    // Lógica para consultar a tarefa no banco de dados
                    if ($id) {
                        $stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = :id");
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        
                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<h3>Tarefa Encontrada:</h3>";
                            echo "<strong>ID:</strong> " . htmlspecialchars($row['id']) . "<br/>";
                            echo "<strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br/>";
                            echo "<strong>Descrição:</strong> " . htmlspecialchars($row['descricao']) . "<br/>";
                            echo "<strong>Status:</strong> " . htmlspecialchars($row['status']) . "<br/>";
                        } else {
                            echo "<p>Tarefa com ID '$id' não encontrada.</p>";
                        }
                    } else {
                        echo "<p>Por favor, forneça um ID para consultar.</p>";
                    }
                    break;

                default:
                    echo "<p>Ação não reconhecida.</p>";
                    break;
            }
        }

        // Exibir todas as tarefas cadastradas
        echo "<h2>Todas as Tarefas:</h2>";
        $stmt = $conn->query("SELECT * FROM tarefas");
        $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($tarefas) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Título</th><th>Descrição</th><th>Status</th></tr>";
            foreach ($tarefas as $tarefa) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($tarefa['id']) . "</td>";
                echo "<td>" . htmlspecialchars($tarefa['titulo']) . "</td>";
                echo "<td>" . htmlspecialchars($tarefa['descricao']) . "</td>";
                echo "<td>" . htmlspecialchars($tarefa['status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Não há tarefas cadastradas.</p>";
        }
        ?>
    </main>
</body>
</html>
