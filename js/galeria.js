$(document).ready(function(){

    $('.imggaleria').hide().delay().fadeIn(3000); 
    
});

function addBorder(e){

    e.classList.add('border-red');

}

function removeBorder(e){

    e.classList.remove('border-red');
    
}