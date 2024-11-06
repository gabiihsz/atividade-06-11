<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="stye.css"> <!-- Corrigido o nome do arquivo CSS -->
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
            <label for="id">ID da Tarefa:</label><br/>
            <input type="number" id="id" name="id"><br/><br/>

            <label for="titulo">Título da Tarefa:</label><br/>
            <input type="text" id="titulo" name="titulo" required><br/>

            <label for="descricao">Descrição:</label><br/>
            <textarea id="descricao" name="descricao" required></textarea><br/><br/>

            <!-- Botões para ações -->
            <input type="submit" name="acao" value="Inserir">
            <input type="submit" name="acao" value="Alterar">
            <input type="submit" name="acao" value="Excluir">
            <input type="submit" name="acao" value="Consultar">
        </form>

        <?php
        // Configurações do banco de dados
        $host = 'localhost'; 
        $db = 'db_gereciamentos';
        $user = 'usuarios'; 

        try {
            // Conexão com o banco de dados usando PDO
            $conn = new PDO("mysql:host=$host;dbname=$db", $user,);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            die(); // Para a execução do código em caso de falha na conexão
        }

        // Processamento do formulário
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $acao = $_POST['acao'];
            $id = $_POST['id'] ?? null;
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            switch ($acao) {
                case 'Inserir':
                    // Lógica para inserir a tarefa no banco de dados
                    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao) VALUES (:titulo, :descricao)");
                    $stmt->bindParam(':titulo', $titulo);
                    $stmt->bindParam(':descricao', $descricao);
                    
                    if ($stmt->execute()) {
                        echo "<p>Tarefa '$titulo' cadastrada com sucesso!</p>";
                    } else {
                        echo "<p>Erro ao cadastrar a tarefa.</p>";
                    }
                    break;

                case 'Alterar':
                    // Lógica para alterar a tarefa no banco de dados
                    if ($id) {
                        $stmt = $conn->prepare("UPDATE tarefas SET titulo = :titulo, descricao = :descricao WHERE id = :id");
                        $stmt->bindParam(':titulo', $titulo);
                        $stmt->bindParam(':descricao', $descricao);
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
        ?>
    </main>
</body>
</html>
