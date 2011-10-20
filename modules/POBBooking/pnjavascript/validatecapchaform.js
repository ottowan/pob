  function validateCapchaForm(form) {


    //Check captcha
    var captcha = document.getElementById("captcha");
    if (trimString(captcha.value) == '' ) {

         alert("Please enter text verifier.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        captcha.value = "";
        captcha.style.borderStyle = "solid";
        captcha.style.borderColor = "#FF0000";
        captcha.style.borderWidth = "1px";
        captcha.focus();
        return false;
    } else{
      captcha.style.borderStyle = "solid";
      captcha.style.borderColor = "#00CC00";
      captcha.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }




