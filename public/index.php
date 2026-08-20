<?php
$host = "localhost";
$user = "root";
$password = "root";
$database = "crud_aula";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// INSERIR
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nome, $email);
    $stmt->execute();

    header("Location: index.php");
    exit();
}

// DELETAR
if (isset($_GET['excluir'])) {
    $id = (int) $_GET['excluir'];

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}

// SALVAR EDIÇÃO (POST)
if (isset($_POST['editar'])) {
    $id = (int) $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}

$usuario_editar = null;
if (isset($_GET['editar'])) {
    $id = (int) $_GET['editar'];

    $sql = "SELECT id, nome, email FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario_editar = $stmt->get_result()->fetch_assoc();
}

// BUSCAR USUÁRIOS
$sql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários</title>
</head>

<body>

    <h2><?php echo $usuario_editar ? 'Editar Usuário' : 'Cadastro de Usuários'; ?></h2>
    <form method="POST">
        <?php if ($usuario_editar): ?>
            <input type="hidden" name="id" value="<?php echo $usuario_editar['id']; ?>">
        <?php endif; ?>

        <label>Nome:</label>
        <input type="text" name="nome"
            value="<?php echo ($usuario_editar['nome'] ?? ''); ?>" required>
        <br><br>

        <label>Email:</label>
        <input type="email" name="email"
            value="<?php echo ($usuario_editar['email'] ?? ''); ?>" required>
        <br><br>

        <button type="submit" name="<?php echo $usuario_editar ? 'editar' : 'cadastrar'; ?>">
            <?php echo $usuario_editar ? 'Salvar Alterações' : 'Cadastrar'; ?>
        </button>

        <?php if ($usuario_editar): ?>
            <a href="index.php">Cancelar</a>
        <?php endif; ?>
    </form>

    <h2>Usuários Cadastrados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>

        <?php while ($usuario = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $usuario['id']; ?></td>
                <td><?php echo ($usuario['nome']); ?></td>
                <td><?php echo ($usuario['email']); ?></td>
                <td>
                    <a href="index.php?editar=<?php echo $usuario['id']; ?>">Editar</a> |
                    <a href="index.php?excluir=<?php echo $usuario['id']; ?>">Excluir</a>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>

</html>