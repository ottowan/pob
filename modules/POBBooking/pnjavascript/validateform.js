  function validateForm(form) {
 
    //Check identificational
    var identificational = document.getElementById("identificational");
    if (trimString(identificational.value) == '' || !check_number(trimString(identificational.value))) {

        if(trimString(identificational.value) == ''){
          alert("Please enter your identificational.");
        }else{
          alert("Please enter number only.");
        }

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        identificational.value = "";
        identificational.style.borderStyle = "solid";
        identificational.style.borderColor = "#FF0000";
        identificational.style.borderWidth = "1px";
        identificational.focus();
        return false;
    } else{
      identificational.style.borderStyle = "solid";
      identificational.style.borderColor = "#00CC00";
      identificational.style.borderWidth = "1px";
      document.getElementById('error-identificational').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check titlename
    var titlename = document.getElementById("titlename");
    if (trimString(titlename.value) == '' ) {

         alert("Please select titlename.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        titlename.style.borderStyle = "solid";
        titlename.style.borderColor = "#FF0000";
        titlename.style.borderWidth = "1px";
        titlename.focus();
        return false;
    } else{
      titlename.style.borderStyle = "solid";
      titlename.style.borderColor = "#00CC00";
      titlename.style.borderWidth = "1px";
      document.getElementById('error-titlename').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check firstname
    var firstname = document.getElementById("firstname");
    if (trimString(firstname.value) == '' ) {

         alert("Please enter your firstname.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        firstname.style.borderStyle = "solid";
        firstname.style.borderColor = "#FF0000";
        firstname.style.borderWidth = "1px";
        firstname.focus();
        return false;
    } else{
      firstname.style.borderStyle = "solid";
      firstname.style.borderColor = "#00CC00";
      firstname.style.borderWidth = "1px";
      document.getElementById('error-firstname').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check lastname
    var lastname = document.getElementById("lastname");
    if (trimString(lastname.value) == '' ) {

         alert("Please enter your lastname.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        lastname.style.borderStyle = "solid";
        lastname.style.borderColor = "#FF0000";
        lastname.style.borderWidth = "1px";
        lastname.focus();
        return false;
    } else{
      lastname.style.borderStyle = "solid";
      lastname.style.borderColor = "#00CC00";
      lastname.style.borderWidth = "1px";
      document.getElementById('error-lastname').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check address
    var address = document.getElementById("address");
    if (trimString(address.value) == '' ) {

         alert("Please enter your address.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        address.style.borderStyle = "solid";
        address.style.borderColor = "#FF0000";
        address.style.borderWidth = "1px";
        address.focus();
        return false;
    } else{
      address.style.borderStyle = "solid";
      address.style.borderColor = "#00CC00";
      address.style.borderWidth = "1px";
      document.getElementById('error-address').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check city
    var city = document.getElementById("city");
    if (trimString(city.value) == '' ) {

         alert("Please enter your city.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        city.style.borderStyle = "solid";
        city.style.borderColor = "#FF0000";
        city.style.borderWidth = "1px";
        city.focus();
        return false;
    } else{
      city.style.borderStyle = "solid";
      city.style.borderColor = "#00CC00";
      city.style.borderWidth = "1px";
      document.getElementById('error-city').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check province
    var province = document.getElementById("province");
    if (trimString(province.value) == '' ) {

         alert("Please enter your province.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        province.style.borderStyle = "solid";
        province.style.borderColor = "#FF0000";
        province.style.borderWidth = "1px";
        province.focus();
        return false;
    } else{
      province.style.borderStyle = "solid";
      province.style.borderColor = "#00CC00";
      province.style.borderWidth = "1px";
      document.getElementById('error-province').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }


    //Check country
    var country = document.getElementById("country");
    if (trimString(country.value) == '' ) {

         alert("Please enter your country.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        //titlename.value = "";
        country.style.borderStyle = "solid";
        country.style.borderColor = "#FF0000";
        country.style.borderWidth = "1px";
        country.focus();
        return false;
    } else{
      country.style.borderStyle = "solid";
      country.style.borderColor = "#00CC00";
      country.style.borderWidth = "1px";
      document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check zipcode
    var zipcode = document.getElementById("zipcode");
    if (trimString(zipcode.value) == '' || !check_number(trimString(zipcode.value))) {

        if(trimString(identificational.value) == ''){
          alert("Please enter your zipcode.");
        }else{
          alert("Please enter number only.");
        }

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        zipcode.value = "";
        zipcode.style.borderStyle = "solid";
        zipcode.style.borderColor = "#FF0000";
        zipcode.style.borderWidth = "1px";
        zipcode.focus();
        return false;
    } else{
      zipcode.style.borderStyle = "solid";
      zipcode.style.borderColor = "#00CC00";
      zipcode.style.borderWidth = "1px";
      document.getElementById('error-zipcode').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }


    //Check mobile
    var mobile = document.getElementById("mobile");
    if (trimString(mobile.value) == '' || !check_number(trimString(zipcode.value))) {

        if(trimString(identificational.value) == ''){
          alert("Please enter your mobile number.");
        }else{
          alert("Please enter number only.");
        }

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        mobile.value = "";
        mobile.style.borderStyle = "solid";
        mobile.style.borderColor = "#FF0000";
        mobile.style.borderWidth = "1px";
        mobile.focus();
        return false;
    } else{
      mobile.style.borderStyle = "solid";
      mobile.style.borderColor = "#00CC00";
      mobile.style.borderWidth = "1px";
      document.getElementById('error-mobile').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check phone
    var phone = document.getElementById("phone");
    if (trimString(phone.value) == '' || !check_number(trimString(phone.value))) {

        if(trimString(identificational.value) == ''){
          alert("Please enter your phone number.");
        }else{
          alert("Please enter number only.");
        }

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        phone.value = "";
        phone.style.borderStyle = "solid";
        phone.style.borderColor = "#FF0000";
        phone.style.borderWidth = "1px";
        phone.focus();
        return false;
    } else{
      phone.style.borderStyle = "solid";
      phone.style.borderColor = "#00CC00";
      phone.style.borderWidth = "1px";
      document.getElementById('error-phone').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check email
    var email  = document.getElementById("email");
    if (trimString(email.value)=='') {
      alert("Please enter your email.");
      email.style.borderStyle = "solid";
      email.style.borderColor = "#FF0000";
      email.style.borderWidth = "1px";
      email.focus();
      return false;
    }

    if (!isEmail(email.value)) {
      alert("This email is wrong !!! ");
      email.value = "";
      email.style.borderStyle = "solid";
      email.style.borderColor = "#FF0000";
      email.style.borderWidth = "1px";
      email.focus();
      return false;
    }else{
        email.style.borderStyle = "solid";
        email.style.borderColor = "#00CC00";
        email.style.borderWidth = "1px";
        document.getElementById('error-email').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }


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

    //Check cardType
    var cardType = document.getElementById("cardType");
    if (trimString(cardType.value) == '' ) {

         alert("Please select card type.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        cardType.style.borderStyle = "solid";
        cardType.style.borderColor = "#FF0000";
        cardType.style.borderWidth = "1px";
        cardType.focus();
        return false;
    } else{
      cardType.style.borderStyle = "solid";
      cardType.style.borderColor = "#00CC00";
      cardType.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }




    //Check card number
    var card1 = document.getElementById("card1");
    if (trimString(card1.value) == '' ) {
        alert("Please enter card number.");
        card1.style.borderStyle = "solid";
        card1.style.borderColor = "#FF0000";
        card1.style.borderWidth = "1px";
        card1.focus();
        return false;
    } else if(card1.value.length < 4){
        alert("Please enter 4 digit.");
        card1.style.borderStyle = "solid";
        card1.style.borderColor = "#FF0000";
        card1.style.borderWidth = "1px";
        card1.focus();
        return false;
    } else if(!check_number(trimString(card1.value))){
        alert("Please enter number only.");
        card1.style.borderStyle = "solid";
        card1.style.borderColor = "#FF0000";
        card1.style.borderWidth = "1px";
        card1.focus();
        return false;
    } else if(cardType.value == 'MC'){
        if(card1.substring(0,2)!='51' || card1.substring(0,2)!='52' || card1.substring(0,2)!='53' || card1.substring(0,2)!='54' || card1.substring(0,2)!='55'){
          alert("Card number not match with type!");
          card1.style.borderStyle = "solid";
          card1.style.borderColor = "#FF0000";
          card1.style.borderWidth = "1px";
          card1.focus();
          return false;
        }
    }else if(cardType.value == 'VC'){
        if(card1.substring(0,1)!='4'){
          alert("Card number not match with type!");
          card1.style.borderStyle = "solid";
          card1.style.borderColor = "#FF0000";
          card1.style.borderWidth = "1px";
          card1.focus();
          return false;
        }
    }else if(cardType.value == 'AC'){
        if(card1.substring(0,2)!='34' || card1.substring(0,2)!='37'){
          alert("Card number not match with type!");
          card1.style.borderStyle = "solid";
          card1.style.borderColor = "#FF0000";
          card1.style.borderWidth = "1px";
          card1.focus();
          return false;
        }
    }else{
      card1.style.borderStyle = "solid";
      card1.style.borderColor = "#00CC00";
      card1.style.borderWidth = "1px";
    }


    //Check card number
    var card2 = document.getElementById("card2");
    if (trimString(card1.value) == '' ) {
        alert("Please enter card number.");
        card2.style.borderStyle = "solid";
        card2.style.borderColor = "#FF0000";
        card2.style.borderWidth = "1px";
        card2.focus();
        return false;

    } else if(card2.value.length < 4){
        alert("Please enter 4 digit.");
        card2.style.borderStyle = "solid";
        card2.style.borderColor = "#FF0000";
        card2.style.borderWidth = "1px";
        card2.focus();
        return false;
    } else if(!check_number(trimString(card2.value))){
        alert("Please enter number only.");
        card2.style.borderStyle = "solid";
        card2.style.borderColor = "#FF0000";
        card2.style.borderWidth = "1px";
        card2.focus();
        return false;
    } else{
      card2.style.borderStyle = "solid";
      card2.style.borderColor = "#00CC00";
      card2.style.borderWidth = "1px";
    }


    //Check card number
    var card3 = document.getElementById("card3");
    if (trimString(card3.value) == '' ) {
        alert("Please enter card number.");
        card3.style.borderStyle = "solid";
        card3.style.borderColor = "#FF0000";
        card3.style.borderWidth = "1px";
        card3.focus();
        return false;

    } else if(card3.value.length < 4){
        alert("Please enter 4 digit.");
        card3.style.borderStyle = "solid";
        card3.style.borderColor = "#FF0000";
        card3.style.borderWidth = "1px";
        card3.focus();
        return false;
    } else if(!check_number(trimString(card3.value))){
        alert("Please enter number only.");
        card3.style.borderStyle = "solid";
        card3.style.borderColor = "#FF0000";
        card3.style.borderWidth = "1px";
        card3.focus();
        return false;
    } else{
      card3.style.borderStyle = "solid";
      card3.style.borderColor = "#00CC00";
      card3.style.borderWidth = "1px";
    }


    //Check card number
    var card4 = document.getElementById("card4");
    if (trimString(card4.value) == '' ) {
        alert("Please enter card number.");
        card4.style.borderStyle = "solid";
        card4.style.borderColor = "#FF0000";
        card4.style.borderWidth = "1px";
        card4.focus();
        return false;
    } else if(!check_number(trimString(card4.value))){
        alert("Please enter number only.");
        card4.style.borderStyle = "solid";
        card4.style.borderColor = "#FF0000";
        card4.style.borderWidth = "1px";
        card4.focus();
        return false;
    } else{
      card4.style.borderStyle = "solid";
      card4.style.borderColor = "#00CC00";
      card4.style.borderWidth = "1px";
    }

    //Check card digit match
    var card_no = card1.value + card2.value + card3.value + card4.value;
    var card_no_trim = trimString(card_no);
    var card_no_length = card_no_trim.length;
    if (cardType.value == 'MC' && card_no_length != 16) {
        alert("Card number not match with type.");
        card1.style.borderStyle = "solid";
        card2.style.borderStyle = "solid";
        card3.style.borderStyle = "solid";
        card4.style.borderStyle = "solid";
        card1.style.borderColor = "#FF0000";
        card2.style.borderColor = "#FF0000";
        card3.style.borderColor = "#FF0000";
        card4.style.borderColor = "#FF0000";
        card1.style.borderWidth = "1px";
        card2.style.borderWidth = "1px";
        card3.style.borderWidth = "1px";
        card4.style.borderWidth = "1px";
        card4.focus();
        return false;
    } else if(cardType.value == 'VS' && card_no_length != 16 || card_no_length != 13){
        alert("Card number not match with type.");
        card1.style.borderStyle = "solid";
        card2.style.borderStyle = "solid";
        card3.style.borderStyle = "solid";
        card4.style.borderStyle = "solid";
        card1.style.borderColor = "#FF0000";
        card2.style.borderColor = "#FF0000";
        card3.style.borderColor = "#FF0000";
        card4.style.borderColor = "#FF0000";
        card1.style.borderWidth = "1px";
        card2.style.borderWidth = "1px";
        card3.style.borderWidth = "1px";
        card4.style.borderWidth = "1px";
        card4.focus();
        return false;
    } else if(cardType.value == 'AC' && card_no_length != 15){
        alert("Card number not match with type.");
        card1.style.borderStyle = "solid";
        card2.style.borderStyle = "solid";
        card3.style.borderStyle = "solid";
        card4.style.borderStyle = "solid";
        card1.style.borderColor = "#FF0000";
        card2.style.borderColor = "#FF0000";
        card3.style.borderColor = "#FF0000";
        card4.style.borderColor = "#FF0000";
        card1.style.borderWidth = "1px";
        card2.style.borderWidth = "1px";
        card3.style.borderWidth = "1px";
        card4.style.borderWidth = "1px";
        card4.focus();
        return false;
    } else{
      card1.style.borderStyle = "solid";
      card2.style.borderStyle = "solid";
      card3.style.borderStyle = "solid";
      card4.style.borderStyle = "solid";
      card1.style.borderColor = "#00CC00";
      card2.style.borderColor = "#00CC00";
      card3.style.borderColor = "#00CC00";
      card4.style.borderColor = "#00CC00";
      card1.style.borderWidth = "1px";
      card2.style.borderWidth = "1px";
      card3.style.borderWidth = "1px";
      card4.style.borderWidth = "1px";
    }


    //Check card 
    var card_holder_number = document.getElementById("card_holder_number");
    if (trimString(card_holder_number.value) == '' ) {

         alert("Please enter card holder number.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        card_holder_number.style.borderStyle = "solid";
        card_holder_number.style.borderColor = "#FF0000";
        card_holder_number.style.borderWidth = "1px";
        card_holder_number.focus();
        return false;
    } else{
      card_holder_number.style.borderStyle = "solid";
      card_holder_number.style.borderColor = "#00CC00";
      card_holder_number.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }


    //Check card CVV
    var cardid = document.getElementById("cardid");
    if (trimString(cardid.value) == '' ) {

         alert("Please enter card secure code(CVV).");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        cardid.style.borderStyle = "solid";
        cardid.style.borderColor = "#FF0000";
        cardid.style.borderWidth = "1px";
        cardid.focus();
        return false;
    } else{
      cardid.style.borderStyle = "solid";
      cardid.style.borderColor = "#00CC00";
      cardid.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }



    //Check card expire month
    var card_expire_month = document.getElementById("card_expire_month");
    if (trimString(card_expire_month.value) == '' ) {

         alert("Please select card expire month.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        card_expire_month.style.borderStyle = "solid";
        card_expire_month.style.borderColor = "#FF0000";
        card_expire_month.style.borderWidth = "1px";
        card_expire_month.focus();
        return false;
    } else{
      card_expire_month.style.borderStyle = "solid";
      card_expire_month.style.borderColor = "#00CC00";
      card_expire_month.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check card expire year
    var card_expire_year = document.getElementById("card_expire_year");
    if (trimString(card_expire_year.value) == '' ) {

         alert("Please select card expire year.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        card_expire_year.style.borderStyle = "solid";
        card_expire_year.style.borderColor = "#FF0000";
        card_expire_year.style.borderWidth = "1px";
        card_expire_year.focus();
        return false;
    } else{
      card_expire_year.style.borderStyle = "solid";
      card_expire_year.style.borderColor = "#00CC00";
      card_expire_year.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }




    //Check card expire year
    var card_bank_name = document.getElementById("card_bank_name");
    if (trimString(card_bank_name.value) == '' ) {

         alert("Please enter your card bank name.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        card_bank_name.style.borderStyle = "solid";
        card_bank_name.style.borderColor = "#FF0000";
        card_bank_name.style.borderWidth = "1px";
        card_bank_name.focus();
        return false;
    } else{
      card_bank_name.style.borderStyle = "solid";
      card_bank_name.style.borderColor = "#00CC00";
      card_bank_name.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    //Check card expire year
    var card_issuing_country = document.getElementById("card_issuing_country");
    if (trimString(card_issuing_country.value) == '' ) {

         alert("Please select card issuing country.");

        //document.getElementById('error-identificational').innerHTML = "* Identificational.";
        card_issuing_country.style.borderStyle = "solid";
        card_issuing_country.style.borderColor = "#FF0000";
        card_issuing_country.style.borderWidth = "1px";
        card_issuing_country.focus();
        return false;
    } else{
      card_issuing_country.style.borderStyle = "solid";
      card_issuing_country.style.borderColor = "#00CC00";
      card_issuing_country.style.borderWidth = "1px";
      //document.getElementById('error-country').innerHTML = "<IMG SRC='modules/Booking/pnimages/right.gif' WIDTH='10' HEIGHT='10'>";
    }

    var agree = document.getElementById("agree");
    if(!agree.checked ){
      alert("Please click to agree all Terms & Conditions / Cancellation Policy !");
      agree.style.borderStyle = "solid";
      agree.style.borderColor = "#FF0000";
      agree.style.borderWidth = "1px";
      agree.focus();
      return false;
    }

}

    function trimString (str) {
      str = this != window? this : str;
      return str.replace(/\s+/g, '').replace(/\s+$/g, '');
    }

    function trim(stringToTrim) {
      return stringToTrim.replace(/^\s+|\s+$/g,"");
    }

    function check_number(ch){
      var len, digit;
      if(ch == " "){
        return false;
        len=0;
      }else{
        len = ch.length;
      }
      for(var i=0 ; i<len ; i++) {
        digit = ch.charAt(i)
        if(digit >="0" && digit <="9"){
        
        }else{
          return false;
        }
      }
      return true;
    }

    function isEmail(incoming) 
    {
      var emailstring = incoming;
      var ampIndex = emailstring.indexOf("@");
      var afterAmp = emailstring.substring((ampIndex + 1), emailstring.length);
        // find a dot in the portion of the string after the ampersand only
      var dotIndex = afterAmp.indexOf(".");
        // determine dot position in entire string (not just after amp portion)
      dotIndex = dotIndex + ampIndex + 1;
        // afterAmp will be portion of string from ampersand to dot
      afterAmp = emailstring.substring((ampIndex + 1), dotIndex);
        // afterDot will be portion of string from dot to end of string
      var afterDot = emailstring.substring((dotIndex + 1), emailstring.length);
      var beforeAmp = emailstring.substring(0,(ampIndex));
        //old regex did not allow subdomains and dots in names
        //var email_regex = /^[\w\d\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[\w\d\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~])*\@(((\w+[\w\d\-]*[\w\d]\.)+(\w+[\w\d\-]*[\w\d]))|((\d{1,3}\.){3}\d{1,3}))$/;
      var email_regex = /^\w(?:\w|-|\.(?!\.|@))*@\w(?:\w|-|\.(?!\.))*\.\w{2,3}/ 
        // index of -1 means "not found"
      if ((emailstring.indexOf("@") != "-1") &&
        (emailstring.length > 5) &&
        (afterAmp.length > 0) &&
        (beforeAmp.length > 1) &&
        (afterDot.length > 1) &&
        (email_regex.test(emailstring)) ) {
          return true;
      } else {
          return false;
      }
    }




