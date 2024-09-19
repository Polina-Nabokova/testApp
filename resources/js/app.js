import './bootstrap';
import 'https://code.jquery.com/jquery-3.6.0.min.js';
$(document).ready(function (e) {
    $('#photo').change(function() {
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#image-preview').html('<img src="'+ e.target.result +'">');
            $('.select-btn').text('');
            $('.choose-wrap svg').css('display', 'none');
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('.choose-wrap').click(function (){
        $('#photo').trigger('click');
    });

    $('#generate-users').click(function(e){
         e.preventDefault();
         if(confirm("Press 'Ok' to generate 45 users")) {
            $(this).attr('disabled', true);
            $(this).removeClass("btn-dark").addClass("btn-secondary");
            $('.generate-btn-wrap .preloader-img').css('display','inline-block');
        
            window.location.href = '/users/import';     
        }
          
    });
        
});
