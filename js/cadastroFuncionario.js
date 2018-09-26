$(document).ready(function(){

    $('#cargo').change(function(){
        if ($('#medico').is(':selected')) {
            $('#especialidade').show();
        } else {
            $('#especialidade').hide();
        }
    });

});

function validaData(){

    var dataNascimento =  document.getElementById('data').value;

    var dataAtual = dataNascimento.split('-');

    var data = new Date(dataAtual[0], dataAtual[1] - 1, dataAtual[2]);

    if(data > new Date()){
        alert('Data Inválida');
    }

}