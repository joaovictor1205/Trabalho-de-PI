<?php

require "conexaoBanco.php";

class contato
{
    public $nomeCliente;
    public $emailCliente;
    public $motivoContato;
    public $mensagem;
	
}

function getcontato($conn)
{
    $arraycontato = "";
  
    $SQL = "
        SELECT *
        FROM Contato
        ";
  
    $result = $conn->query($SQL);
    if (! $result)
        throw new Exception('Ocorreu uma falha: ' . $conn->error);
    
    if ($result->num_rows > 0)
    {
        $i = 0;
        while ($row = $result->fetch_assoc())
        {
        $contato = new contato();

        $contato->nome       = $row["nome"];
        $contato->email      = $row["emailContato"];
        $contato->motivo     = $row["motivo"];
        $contato->msg        = $row["msg"];
        

        $arraycontato[$i] = $contato;
        $i++;
        }
    }
    
    return $arraycontato;
}

$arraycontato = "";
$msgErro = "";

try
{
    $conn = conectaAoMySQL();
    $arraycontato = getcontato($conn);  
}
catch (Exception $e)
{
    $msgErro = $e->getMessage();
}

?>

<!DOCTYPE <!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <title>Clínica - Lista Contato</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/listaContato.css?128483">
</head>

<body>
        
    <div id="contato">
        <div class="container">
            <nav id="header" class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="homePrivado.html">Área Restrita</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a href="cadastroFuncionario.php">Novo Funcionário</a></li>
                        <li><a href="listaFuncionario.html">Listar Funcionários</a></li>
                        <li class="active"><a href="listaContato.html">Listar Contatos</a></li>
                        <li><a href="listaAgendamento.html">Listar Agendamentos</a></li>
                        <a href="home.html"><button type="button" class="btn btn-default" id="voltar">Voltar</button></a>

                    </ul>
                </div>
            </nav>
        </div>

    <div class="container mainContent">
  
  <h3>Clientes Cadastrados</h3>

  <table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Motivo do Contato</th>
            <th>Mensagem</th>
        </tr>
    </thead>
    
    <tbody>

    <?php
	
        if ($arraycontato != "")
        {
            foreach ($arraycontato as $contato)
            {       
                echo "
                <tr>
                <td>$contato->nome</td>
                <td>$contato->email</td>
                <td>$contato->motivo</td>
                <td>$contato->msg</td>
                </tr>      
                ";
            }
        }		
    ?>  
		
    </tbody>
  </table>
  
  <?php
    if ($msgErro != "")
    echo "<p class='text-danger'>A operação não pode ser realizada: $msgErro</p>";
    ?>   
</div>

</body>

</html>