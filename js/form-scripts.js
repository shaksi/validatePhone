
$("#verifyPhone").validator().on("submit", function (event) {
    if (event.isDefaultPrevented()) {
        // handle the invalid form...
        formError();
        submitMSG(false, "Did you fill in the form properly?");
    } else {
        // everything looks good!
        //copy number and submit form
        // var copyText = document.getElementById("mobile");
        // copyText.select();
        // document.execCommand("copy");
        // console.log("Copied the text: " + copyText.value);
        event.preventDefault();
        submitForm();
    }
});


function submitForm(){
    // Initiate Variables With Form Content
    var mobile = $("#mobile").val();
    Clipboard.copy(mobile);

    console.log(mobile);
    $.ajax({
        type: "POST",
        url: "process.php",
        data: "mobile=" + mobile,
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

window.Clipboard = (function(window, document, navigator) {
    var textArea,
        copy;

    function isOS() {
        return navigator.userAgent.match(/ipad|iphone/i);
    }

    function createTextArea(text) {
        textArea = document.createElement('textArea');
        textArea.value = text;
        document.body.appendChild(textArea);
    }

    function selectText() {
        var range,
            selection;

        if (isOS()) {
            range = document.createRange();
            range.selectNodeContents(textArea);
            selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            textArea.setSelectionRange(0, 999999);
        } else {
            textArea.select();
        }
    }

    function copyToClipboard() {        
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }

    copy = function(text) {
        createTextArea(text);
        selectText();
        copyToClipboard();
    };

    return {
        copy: copy
    };
})(window, document, navigator);
