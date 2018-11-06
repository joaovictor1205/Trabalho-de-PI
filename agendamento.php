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
	$formProcSucesso = false;
	$msgErro = "";
	
    $codPaciente = $codAgendamento = $nome = $telefone = $datadata = $hora = "";
  
	$nome       = filtraEntrada($_POST["nome"]);     
    $telefone   = filtraEntrada($_POST["telefone"]);
    $datadata   = filtraEntrada($_POST["datadata"]);     
    $hora       = filtraEntrada($_POST["hora"]);
    
    try
    {
        $conn = conectaAoMySQL();
        
        $conn->begin_transaction();
    
        $sql = "
            INSERT INTO Paciente (codPaciente, nome, telefone) 
            VALUES (NULL, '$nome', $telefone)
        ";

        echo $sql;
                
        if (! $conn->query($sql))
            throw new Exception('Erro ao inserir os dados');
    
        $id_paciente = mysqli_insert_id($conn);
    
        $sql = "
            INSERT INTO Agenda (codAgendamento, codPaciente, datadata, hora)
            VALUES (NULL, $id_paciente, '$datadata', $hora) 
        ";
            
        if (! $conn->query($sql))
            throw new Exception('Erro ao inserir na tabela agenda');
    
        //se nenhuma excecao foi lancada, efetiva as operacoes
        $conn->commit();
        echo "Transacao executada com sucesso";
        $formProcSucesso = true;
    }

    catch (Exception $e)
    {
        $conn->rollback();
        echo "Ocorreu um erro na transacao: " . $e->getMessage();
    }
}
?>


<!DOCTYPE <!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <title>Clínica - Agendamento</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/agendamento.css">

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
        
    <div id="agendamento">
        <div class="container">
            <nav id="header" class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">Nossa Clínica</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a href="home.html">Home</a></li>
                        <li><a href="galeria.html">Galeria</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <li class="active"><a href="agendamento.php">Agendamento</a></li>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#abrirModal" id="botaoModal">Entrar</button>
                    </ul>
                </div>
            </nav>

            <div class="caixa">

                <h1>Agende uma consulta</h1>
                    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">


                    
                        <div class="form-group row">
                            <div class="col-lg-12 mb-2">
                            <?php
                                $con = new mysqli("fdb22.awardspace.net", "2838970_bancodedados", "trabalhodepi123","2838970_bancodedados") or die (mysql_error());
                                $query = $con->query("SELECT especialidade FROM Funcionario");
                            ?>
                            <label class="control-label col-sm-12" for="especialidade">Especialidade médica:</label>
                                <select class="form-control" id="especialidade" name="especialidade">
                                <?php while($reg = $query->fetch_array()) { ?>
                                    <option value="<?php echo $reg["especialidade"]?>"> <?php echo $reg["especialidade"]?> </option>
                                <?php }?>
                                </select>
                            </div>
                        </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-12" for="nomemedico">Nome do Médico Especialista</label>
	                            <div class="col-sm-10">
                                <?php
                                    $con = new mysqli("fdb22.awardspace.net", "2838970_bancodedados", "trabalhodepi123","2838970_bancodedados") or die (mysql_error());
                                    $query = $con->query("SELECT nome FROM Funcionario");
                                ?>
                                    <select name="ativo" class="form-control">
                                        <?php while($reg = $query->fetch_array()) { ?>
                                        <option value="<?php echo $reg["nome"]?>"> <?php echo $reg["nome"]?> </option>
                                        <?php }?>
                                    </select>	   
	                            </div>
                            </div>
                            
                        <div class="form-group row">
                            <div class="col-lg-12 mb-2">
                            <label for="nome">Nome do paciente:</label>
                                <input class="form-control" type="text" id="nome" name="nome">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mb-2">
                            <label for="telefone">Telefone do paciente:</label>
                                <input class="form-control" type="text" id="telefone" name="telefone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="datadata">Data da Consulta</label>
                            <div class="col-sm-12"> 
                                <input type="date" maxlength="10" class="form-control" name="datadata" id="datadata" maxlength="10" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" min="2018-11-07" placeholder="Data de Consulta" required="required">
                            </div>
                        </div>

                        <div class="form-group" >
                            <label class="control-label col-sm-12" for="hora">Horario Disponível para Consulta</label>
	                        <div class="col-lg-12 mb-2"> 
                                <select name="hora" id="hora" class="form-control" required>
                                    <option value="08">08:00</option>
                                    <option value="09">09:00</option>
                                    <option value="10">10:00</option>
                                    <option value="11">11:00</option>
                                    <option value="12">12:00</option>
                                    <option value="13">13:00</option>
                                    <option value="14">14:00</option>
                                    <option value="15">15:00</option>
                                    <option value="16">16:00</option>
                                    <option value="17">17:00</option>
                                </select>
                            </div>
                        </div>
                      
                        <input type="submit" name="enviar" value="Enviar" id="enviar" class="btn btn-success">

                    </form>
            </div>

        </div>

        <?php 		
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {  
                if ($formProcSucesso == true)
                    echo "<h3 class='text-success'>Agendamento realizado com sucesso!</h3>";
                else
                    echo "<h3 class='text-danger'>Agendamento não realizado $msgErro</h3>";
            }
        ?>

    </div>

</body>

</html>