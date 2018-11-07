<?php

require "conexaoBanco.php";

class dadosfuncionario
{
    public $nome;
    public $sexo;
    public $cargo;
    public $logradouro;
    public $cidade;
}

function getdadosfuncionario($conn)
{
    $arraydadosfuncionario = "";
  
    $SQL = "
        SELECT *
        FROM Funcionario, EnderecoFunc
        WHERE Funcionario.nome = EnderecoFunc.nome
    ";

    $result = $conn->query($SQL);
    if (! $result)
        throw new Exception('Ocorreu uma falha: ' . $conn->error);
        
    if ($result->num_rows > 0)
    {
    $i = 0;

    while ($row = $result->fetch_assoc())
    {
        $dadosfuncionario = new dadosfuncionario();

        $dadosfuncionario->nome          = $row["nome"];
        $dadosfuncionario->sexo          = $row["sexo"];
        $dadosfuncionario->cargo         = $row["cargo"];
        $dadosfuncionario->rg   		 = $row["rg"];
        $dadosfuncionario->logradouro    = $row["logradouro"];
        $dadosfuncionario->cidade  	     = $row["cidade"];
        
        $arraydadosfuncionario[$i] = $dadosfuncionario;
        $i++;
    }
  }
  
  return $arraydadosfuncionario;
}

$arraydadosfuncionario = "";
$msgErro = "";

try
{
    $conn = conectaAoMySQL();
    $arraydadosfuncionario = getdadosfuncionario($conn);  
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
    <title>Clínica - Funcionários</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/listaFuncionario.css?128483">
</head>

<body>

<div id="funcionario">
        <div class="container">
            <nav id="header" class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="homePrivado.html">Área Restrita</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a href="cadastroFuncionario.php">Novo Funcionário</a></li>
                        <li class="active"><a href="listaFuncionario.html">Listar Funcionários</a></li>
                        <li><a href="listaContato.html">Listar Contatos</a></li>
                        <li><a href="listaAgendamento.html">Listar Agendamentos</a></li>
                        <a href="home.html"><button type="button" class="btn btn-default" id="voltar">Voltar</button></a>

                    </ul>
                </div>
            </nav>
        </div>
        
<div class="container mainContent">
  
  <h3>Funcionários Cadastrados</h3>

  <table class="table table-striped">
    <thead>
        <tr>
        <th>Nome</th>
        <th>Sexo</th>
        <th>Cargo</th>
        <th>RG</th>
		<th>Logradouro</th>
		<th>Cidade</th>
      </tr>
    </thead>
    
    <tbody>
		
	<?php
        if ($arraydadosfuncionario != "")
        {
            foreach ($arraydadosfuncionario as $dadosfuncionario)
            {       
                echo "
                <tr>
                    <td>$dadosfuncionario->nome</td>
                    <td>$dadosfuncionario->sexo</td>
                    <td>$dadosfuncionario->cargo</td>
                    <td>$dadosfuncionario->rg</td>	
                    <td>$dadosfuncionario->logradouro</td>
                    <td>$dadosfuncionario->cidade</td>
                </tr>      
                ";
            }
        }	
    ?>    
	
    </tbody>
    </table>
  
    <?php
        if ($msgErro != "")
            echo "<p class='text-danger'>Operação negada: $msgErro</p>";
    ?>
   
</div>
</body>

</html>