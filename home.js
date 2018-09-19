$(document).ready(function(){

    //////////mostrar/ocultar///////////

    $("#SejaBemVindo").click(function(){
        $("#slide").slideToggle(500);
    });

    $("#Missao").click(function(){
        $("#slide2").slideToggle(500);
    });

    $("#Valores").click(function(){
        $("#slide3").slideToggle(500);
    });

    /////////////////////////////////

    //////////////home///////////////

    $("#home").mouseenter(function(){
        $("#home").animate({
            padding:'10px'
        });
    });

    $("#home").mouseleave(function(){
        $("#home").animate({
            padding: '-10px'
        });
    });

    /////////////////////////////////

    ////////////galeria///////////////

    $("#galeria").mouseenter(function(){
        $("#galeria").animate({
            padding:'10px'
        });
    });
    
    $("#galeria").mouseleave(function(){
        $("#galeria").animate({
            padding: '-10px'
        });
    });

    /////////////////////////////////

    ////////////contato///////////////

    $("#contato").mouseenter(function(){
        $("#contato").animate({
            padding:'10px'
        });
    });
    
    $("#contato").mouseleave(function(){
        $("#contato").animate({
            padding: '-10px'
        });
    });

    /////////////////////////////////

    //////////agendamento////////////

    $("#agendamento").mouseenter(function(){
        $("#agendamento").animate({
            padding:'10px'
        });
    });
    
    $("#agendamento").mouseleave(function(){
        $("#agendamento").animate({
            padding: '-10px'
        });
    });

    /////////////////////////////////

});