<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtendo dados do POST
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $curso_id = $_POST['curso_id'] ?? '';

    // Verificar se os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($curso_id)) {
        echo "<p>Todos os campos devem ser preenchidos.</p>";
        exit;
    }

    $data = array("nome" => $nome, "email" => $email, "fk_cursos_id_curso" => $curso_id);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context  = stream_context_create($options);
    $url = 'http://localhost/exercicio/api.php/alunos';
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        echo "<p>Erro ao adicionar aluno: " . htmlspecialchars($error['message']) . "</p>";
    } else {
        echo "<script>
                alert('Operação concluída com sucesso!');
                window.location.href = 'lista_alunos.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Cadastro de Aluno</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

<?php include 'menu_principal.php'; ?>

<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-xl-8 col-md-10 m-auto">
                        <h2>Insere novo aluno</h2>
                        <form action="cadastra_aluno.php" method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Aluno</label>
                                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do aluno" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email do Aluno</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Digite o email do aluno" required>
                            </div>

                            <div class="mb-3">
                                <label for="cursos-dropdown" class="form-label">Nome do Curso</label>
                                <?php
                                    $url = 'http://localhost/exercicio/api.php/cursos';
                                    $response = file_get_contents($url);
                                    $data = json_decode($response, true);

                                    if (isset($data['dados'])) {
                                        echo '<select class="form-select" id="cursos-dropdown" name="curso_id" required>';
                                        echo '<option value="" disabled selected>Selecione um curso</option>';
                                        foreach ($data['dados'] as $curso) {
                                            echo '<option value="' . $curso['id_curso'] . '">' . htmlspecialchars($curso['nome_curso']) . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo '<p>Nenhum curso encontrado.</p>';
                                    }
                                ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="styleSelector"> </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
