<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Aluno - Média</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h2>Calculadora de Notas</h2>
        <form method="POST">
            <input type="number" step="0.1" name="nota1" placeholder="Nota do 1º Bimestre" required min="0" max="10">
            <input type="number" step="0.1" name="nota2" placeholder="Nota do 2º Bimestre" required min="0" max="10">
            <button type="submit">Calcular Resultado</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $n1 = $_POST['nota1'];
            $n2 = $_POST['nota2'];
            $media = ($n1 + $n2) / 2;
            
            echo "<div class='result'>";
            echo "<h3>Média Final: " . number_format($media, 1) . "</h3>";
            if ($media >= 6) {
                echo "<p class='approved'>Parabéns, você foi Aprovado!</p>";
            } else {
                echo "<p class='danger'>Atenção, você ficou Recuperação.</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
