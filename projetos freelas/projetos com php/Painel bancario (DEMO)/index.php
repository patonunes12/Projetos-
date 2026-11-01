<?php
session_start();

if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 1000.00;
    $_SESSION['extrato'] = [];
}

$mensagem = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST['acao'] ?? '';
    $valor = floatval($_POST['valor'] ?? 0);

    if ($acao === 'depositar' && $valor > 0) {
        $_SESSION['saldo'] += $valor;
        $_SESSION['extrato'][] = "Crédito: + R$ " . number_format($valor, 2, ',', '.');
        $mensagem = "Depósito de R$ " . number_format($valor, 2, ',', '.') . " realizado com sucesso.";
        $status = "sucesso";
    } 
    elseif ($acao === 'sacar' && $valor > 0) {
        if ($valor <= $_SESSION['saldo']) {
            $_SESSION['saldo'] -= $valor;
            $_SESSION['extrato'][] = "Débito: - R$ " . number_format($valor, 2, ',', '.');
            $mensagem = "Saque de R$ " . number_format($valor, 2, ',', '.') . " efetuado.";
            $status = "sucesso";
        } else {
            $mensagem = "Operação cancelada: Saldo insuficiente.";
            $status = "erro";
        }
    }
    elseif ($acao === 'transferir' && $valor > 0) {
        $conta = $_POST['conta'] ?? '';
        if ($valor <= $_SESSION['saldo'] && !empty($conta)) {
            $_SESSION['saldo'] -= $valor;
            $_SESSION['extrato'][] = "Transf. para Ag/Cc $conta: - R$ " . number_format($valor, 2, ',', '.');
            $mensagem = "Transferência de R$ " . number_format($valor, 2, ',', '.') . " enviada.";
            $status = "sucesso";
        } else {
            $mensagem = "Falha ao transferir: Saldo insuficiente ou dados ausentes.";
            $status = "erro";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Internet Banking Corporate</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7f6; margin: 0; padding: 30px; }
        .dashboard { max-width: 900px; margin: auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; }
        .bank-header { background: #0a2540; color: #fff; padding: 30px; display: flex; justify-content: space-between; align-items: center; }
        .bank-header h2 { margin: 0; }
        .saldo-box { text-align: right; }
        .saldo-box span { display: block; font-size: 12px; color: #00d4b2; }
        .saldo-box strong { font-size: 24px; }
        .grid-operations { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 30px; }
        .op-card { border: 1px solid #e6ebf1; padding: 20px; border-radius: 8px; background: #f8f9fa; }
        .op-card h4 { margin-top: 0; color: #0a2540; border-bottom: 2px solid #e6ebf1; padding-bottom: 8px; }
        .form-inline { display: flex; gap: 10px; margin-top: 15px; }
        input[type="number"], input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; }
        button { background: #635bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #4f46e5; }
        .alert { padding: 15px; margin: 0 30px 20px 30px; border-radius: 6px; font-weight: 500; }
        .alert-sucesso { background: #e2fbf4; color: #008767; border: 1px solid #a3ebd6; }
        .alert-erro { background: #fde8e8; color: #e02424; border: 1px solid #f8b4b4; }
        .extrato-list { list-style: none; padding: 0; max-height: 200px; overflow-y: auto; }
        .extrato-list li { padding: 8px 0; border-bottom: 1px dashed #ced4da; font-size: 14px; }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="bank-header">
        <div>
            <h2>victor Bank S.e.n.a.c.</h2>
            <p>Agência: 0001 | Conta: 10485-9</p>
        </div>
        <div class="saldo-box">
            <span>SALDO DISPONÍVEL</span>
            <strong>R$ <?php echo number_format($_SESSION['saldo'], 2, ',', '.'); ?></strong>
        </div>
    </div>

    <br>

    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-<?php echo $status; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <div class="grid-operations">
        <div class="op-card">
            <h4>Depósitos e Saques</h4>
            <form method="POST">
                <input type="number" step="0.01" name="valor" placeholder="R$ 0,00" required min="0.01">
                <div class="form-inline">
                    <button type="submit" name="acao" value="depositar" style="background:#22c55e;">Depositar</button>
                    <button type="submit" name="acao" value="sacar" style="background:#ef4444;">Sacar</button>
                </div>
            </form>
        </div>

        <div class="op-card">
            <h4>Transferência Eletrônica (TED/PIX)</h4>
            <form method="POST">
                <input type="text" name="conta" placeholder="Destinatário (Agência/Conta)" required style="margin-bottom:10px; display:block; width:calc(100% - 22px);">
                <div class="form-inline">
                    <input type="number" step="0.01" name="valor" placeholder="R$ 0,00" required min="0.01">
                    <button type="submit" name="acao" value="transferir">Transferir</button>
                </div>
            </form>
        </div>

        <div class="op-card" style="grid-column: span 2;">
            <h4>Extrato da Conta</h4>
            <ul class="extrato-list">
                <?php if (empty($_SESSION['extrato'])): ?>
                    <li style="color:#6b7280;">Nenhuma movimentação encontrada neste período.</li>
                <?php else: ?>
                    <?php foreach (array_reverse($_SESSION['extrato']) as $item): ?>
                        <li><?php echo $item; ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
