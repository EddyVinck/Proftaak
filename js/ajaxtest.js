document.ready(function() {
        $.ajax({
            url: "updateVerifiedStatus.ajax.php",
            type: "POST",
            dataType: "json",
            data: { "userId":userId, "userRole": userRole },
            error: function (error) {                
                console.log(error);
            },
            success: function () {
                alert();
            }
        }).success(function () {
            alert();           
            var correspondingButton = $("#verifiedButton" + rowCounter);
            if( correspondingButton.hasClass('red') )
            {
                console.log(correspondingButton.hasClass('red'));
                correspondingButton.classList.remove('red');
                correspondingButton.classList.add('green');                
            } else {
                correspondingButton.classList.remove('green');
                correspondingButton.classList.add('red');   
            }
        })
});  
        