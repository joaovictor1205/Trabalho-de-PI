<?php

require "conexaoBanco.php";

function filtraEntrada($dado) 
{
	$dado = trim($dado);
	$dado = stripslashes($dado);
	$dado = htmlspecialchars($dado);

	return $dado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$msgErro = "";

	$nome = $emailContato = $msg = $motivo = "";

	$nome         = filtraEntrada($_POST["nome"]);     
	$emailContato = filtraEntrada($_POST["emailContato"]);
	$msg          = filtraEntrada($_POST["msg"]);
    $motivo       = filtraEntrada($_POST["motivo"]);
   
	try
	{    
		$conn = conectaAoMySQL();

		$sql = "
		  INSERT INTO Contato (id, nome, emailContato, msg, motivo)
		  VALUES (null, '$nome', '$emailContato', '$msg', '$motivo')
		";

		if (! $conn->query($sql))
		  throw new Exception("Falha no envio: " . $conn->error);
	}
	catch (Exception $e)
	{
		$msgErro = $e->getMessage();
	}
}

?>


<!DOCTYPE <!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <title>Clínica - Contato</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/contato.css">

</head>

<body>

    <div class="modal fade" id="abrirModal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLongTitle">Entrar</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label for="email">Usuário:</label>
                        <input type="email" class="form-control" id="email" name="email">

                        <br>

                        <label for="pwd">Senha:</label>
                        <input type="password" class="form-control" id="pwd" name="pwd">
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        <a href="homePrivado.html"><button type="button" class="btn btn-success">Entrar</button></a>
                    </div>
                </div>
            </div>
        </div>

<div id="contato">
    <div class="container">
        <nav id="header" class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Nossa Clínica</a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="home.html">Home</a></li>
                    <li><a href="galeria.html">Galeria</a></li>
                    <li class="active"><a href="contato.php">Contato</a></li>
                    <li><a href="agendamento.php">Agendamento</a></li>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#abrirModal" id="botaoModal">Entrar</button>
                </ul>
            </div>
        </nav>
        <div class="caixa">

            <h1>Envie sua Mensagem</h1>

                <form class="form" name="formulario" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <div class="form-group row">
                        <label for="nome">Nome:</label>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" id="nome" name="nome" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="emailContato">Email:</label>
                        <div class="col-sm-4">
                            <input class="form-control" type="emailContato" id="emailContato" name="emailContato" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="msg">Mensagem:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="msg" name="msg" required></textarea>
                        </div>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label" for="motivo">Motivo:</label>
                        <div>
                            <input class="form-check-input" type="radio" id="reclamacao" name="motivo" value="Reclamacao">Reclamação
                            <input class="form-check-input" type="radio" id="sugestao" name="motivo" value="Sugestao">Sugestão
                            <input class="form-check-input" type="radio" id="elogio" name="motivo" value="Elogio">Elogio
                            <input class="form-check-input" type="radio" id="duvida" name="motivo" value="Sugestao">Dúvida
                        </div>
                    </div>

                    <input type="submit" name="enviar" value="Enviar" id="enviar" class="btn btn-success">
                    <a href="home.html"><button type="button" name="voltar" id="voltar" class="btn btn-danger">Voltar</button></a>

                </form>
        </div>
    </div>

    <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {  
            if ($msgErro == "")
                echo "<h3 class='text-success'>Mensagem Enviada com sucesso!</h3>";
            else
                echo "<h3 class='text-danger'>Não foi possível enviar a mensagem: $msgErro</h3>";
        }
    ?>

</div>

</body>

</html>