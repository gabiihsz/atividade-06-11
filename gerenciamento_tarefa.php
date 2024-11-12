<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="style.css"> <!-- Link para arquivo CSS externo -->
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
            <!-- Botões para ações -->
            <input type="submit" name="acao" value="Inserir">
            <input type="submit" name="acao" value="Alterar">
            <input type="submit" name="acao" value="Excluir">
            <input type="submit" name="acao" value="Consultar">
        </form>

        <?php
        // Configurações do banco de dados (db_gereciamentos)
        $host = 'localhost';
        $dbname = 'db_gereciamentos';
        $username = 'root';
        $password = '';

        try {
            // Conexão com o banco de dados usando PDO
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "<p>Erro na conexão com o banco: " . $e->getMessage() . "</p>";
            die();
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
                    if (!empty($titulo) && !empty($descricao) && !empty($status)) {
                        $stmt = $conn->prepare("INSERT INTO tbl_tarefas (tar_setor, tar_prioridade, tar_descricao, tar_status, usu_codigo) VALUES (:setor, :prioridade, :descricao, :status, :usu_codigo)");
                        $stmt->bindParam(':setor', $titulo);
                        $stmt->bindParam(':prioridade', 'Média'); // Exemplo de prioridade fixa
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':status', $status);
                        $stmt->bindParam(':usu_codigo', 1); // Exemplo de código de usuário fixo
                        if ($stmt->execute()) {
                            echo "<p>Tarefa '$titulo' cadastrada com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao cadastrar a tarefa.</p>";
                        }
                    } else {
                        echo "<p>Preencha todos os campos obrigatórios!</p>";
                    }
                    break;

                case 'Alterar':
                    if ($id && !empty($titulo) && !empty($descricao) && !empty($status)) {
                        $stmt = $conn->prepare("UPDATE tbl_tarefas SET tar_setor = :setor, tar_descricao = :descricao, tar_status = :status WHERE tar_codigo = :id");
                        $stmt->bindParam(':setor', $titulo);
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':status', $status);
                        $stmt->bindParam(':id', $id);
                        if ($stmt->execute()) {
                            echo "<p>Tarefa com ID '$id' alterada com sucesso!</p>";
                        } else {
                            echo "<p>Erro ao alterar a tarefa.</p>";
                        }
                    } else {
                        echo "<p>Preencha todos os campos e forneça um ID válido para alterar.</p>";
                    }
                    break;

                case 'Excluir':
                    if ($id) {
                        $stmt = $conn->prepare("DELETE FROM tbl_tarefas WHERE tar_codigo = :id");
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
                    if ($id) {
                        $stmt = $conn->prepare("SELECT * FROM tbl_tarefas WHERE tar_codigo = :id");
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<h3>Tarefa Encontrada:</h3>";
                            echo "<strong>ID:</strong> " . htmlspecialchars($row['tar_codigo']) . "<br/>";
                            echo "<strong>Título:</strong> " . htmlspecialchars($row['tar_setor']) . "<br/>";
                            echo "<strong>Descrição:</strong> " . htmlspecialchars($row['tar_descricao']) . "<br/>";
                            echo "<strong>Status:</strong> " . htmlspecialchars($row['tar_status']) . "<br/>";
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

        // Exibição de tarefas
        echo "<h2>Usuários e Tarefas:</h2>";
        $query = "
            SELECT 
                u.usu_nome AS usuario,
                t.tar_codigo AS codigo_tarefa,
                t.tar_setor AS setor,
                t.tar_prioridade AS prioridade,
                t.tar_descricao AS descricao,
                t.tar_status AS status
            FROM tbl_usuarios u
            LEFT JOIN tbl_tarefas t ON u.usu_codigo = t.usu_codigo
            ORDER BY u.usu_nome, t.tar_codigo";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($dados) {
            echo "<table border='1'>";
            echo "<tr><th>Usuário</th><th>ID Tarefa</th><th>Setor</th><th>Prioridade</th><th>Descrição</th><th>Status</th></tr>";
            foreach ($dados as $linha) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($linha['usuario']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['codigo_tarefa']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['setor']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['prioridade']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['descricao']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma tarefa encontrada.</p>";
        }
        ?>
    </main>
</body>
</html>
