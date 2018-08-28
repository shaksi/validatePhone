
$("#verifyPhone").validator().on("submit", function (event) {
    if (event.isDefaultPrevented()) {
        // handle the invalid form...
        formError();
        submitMSG(false, "Did you fill in the form properly?");
    } else {
        // everything looks good!
        //copy number and submit form
        var copyText = document.getElementById("mobile");
        copyText.select();
        document.execCommand("copy");
        alert("Copied the text: " + copyText.value);
        event.preventDefault();
        submitForm();
    }
});


function submitForm(){
    // Initiate Variables With Form Content
    var mobile = $("#mobile").val();
    var code = $("#code").val();
    console.log(mobile);
    $.ajax({
        type: "POST",
        url: "process.php",
        data: "mobile=" + mobile +'&code='+code,
        success : function(text) {
            console.log(text);
            response = JSON.parse(text);
            if (response.status){
                formSuccess();
            } else {
                formError(response.field);
                submitMSG(false,response.data);
            }
        }
    });
}
function formSuccess(){
    $("#verifyPhone")[0].reset();
    // $("#mainForm").hide();
    // $(window).scrollTop(0);
    $(".btn-primary").hide();
    submitMSG(true, response.data);
}

function formError(field){
    $("#verifyPhone").removeClass().addClass('form-signin shake animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
        $(this).removeClass();
    });
}

function submitMSG(valid, msg){
    if(valid){
        var msgClasses = " text-center tada animated text-success";
    } else {
        var msgClasses = " text-center text-danger";
    }
    $("#msgSubmit").removeClass().addClass(msgClasses).text(msg);
}