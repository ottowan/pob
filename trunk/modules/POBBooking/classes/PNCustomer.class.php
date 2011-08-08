<?php
class PNCustomer extends PNObject {
    function PNCustomer($init=null, $where='') {
        $this->PNObject();

        $this->_objType       = 'pobbooking_customer';
        $this->_objField      = 'id';
        $this->_objPath       = 'form';

        $this->_init($init, $where);
    }


    /*
  function validate(){
    $form = FormUtil::getPassedValue ('form', false);
    $is_validate = true;
    $error = array();

    if (empty($form['identificational'])){
      $error[] = "Please enter your identificational/passport no.";
    }

    if (empty($form['titlename'])){
      $error[] = "Please select Titlename.";
    }

    if (empty($form['firstname'])){
      $error[] = "Please enter your First name.";
    }

    if (empty($form['lastname'])){
      $error[] = "Please enter your Last name/Surname.";
    }


    if (empty($form['address'])){
      $error[] = "Please enter your Address.";
    }


    if (empty($form['city'])){
      $error[] = "Please enter your Town/City.";
    }

    if (empty($form['country'])){
      $error[] = "Please select your Country.";
    }

    if (empty($form['zipcode'])){
      $error[] = "Please enter your Zipcode.";
    }

    if (empty($form['mobile'])){
      $error[] = "Please enter your Mobile Phone.";
    }

    if (empty($form['phone'])){
      $error[] = "Please enter your  ome Phone.";
    }

    if (empty($form['email'])){
      $error[] = "Please enter your Email.";
    }

    $session_captcha = SessionUtil::getVar('SECURITY_CAPTCHA');
    if (empty($form['captcha'])){
      $error[] = "Please enter Verification text.";
    }else if( $session_captcha != md5(base64_encode($form['captcha']))){
      //$error[] = "Verification text is not correct.";
      $error[] = "Captcha failed.";
    }

    if (empty($form['cardType'])){
      $error[] = "Please select your card type.";
    }

    if (empty($form['card1']) || empty($form['card2']) || empty($form['card3']) || empty($form['card4'])){
      $error[] = "Please enter your card number.";
    }

    if (empty($form['cardid'])){
      $error[] = "Please enter your card type.";
    }

    if (empty($form['card_holder_number'])){
      $error[] = "Please enter your holder number.";
    }

    if (empty($form['card_expire_month'])){
      $error[] = "Please select expire month.";
    }

    if (empty($form['card_expire_year'])){
      $error[] = "Please select expire year.";
    }

    if (empty($form['card_bank_name'])){
      $error[] = "Please enter your bank name.";
    }


    if (empty($form['card_issuing_country'])){
      $error[] = "Please select issuing country.";
    }

    //Conclude the result
    if($error){
      $is_validate = false;
      SessionUtil::setVar('ERROR_MESSAGE', $error, '/', true, true);
    }else{
      $is_validate = true;
    }


    //$is_validate = false;

    /*
    return $is_validate;
  }
    */


    function insertPreProcess() {
        //get
        $form = FormUtil::getPassedValue ('form', false );
        
        $card_exp_month = $this->_objData['card_exp_month'];
        $card_exp_year = substr($this->_objData['card_exp_year'], -2);
        $cardexpiredate = $card_exp_month.$card_exp_year;
        //$roomstays = $this->_objData['roomstays'];
        //insert
        $this->_objData['cardexpire'] = $cardexpiredate;
        return true;
    }


    function insertPostProcess() {
        Loader::loadClass('DataUtilEx', "modules/Booking/pnincludes");

        $id = DBUtil::getInsertID ($this->_objType, $this->_objField);
        $refid = "B".$id+1000;
        
        $object = array('refid'=>$refid);
        $where  = " cus_id = ".$id;
        DBUtil::updateObject($object,'pobbooking_customer',$where);

        if ($_POST['forward'] ) {
            $forward  = $_POST['forward'];
            //pnRedirect($list_url);
            //return true;
        }

        //Get form value to insert booking
/*
        $pre_checkin_date = SessionUtil::getVar('checkin_date');
        $pre_checkout_date = SessionUtil::getVar('checkout_date');
        $checkin_date = date('Y-m-d', strtotime($pre_checkin_date));
        $checkout_date = date('Y-m-d', strtotime($pre_checkout_date));
        $night = SessionUtil::getVar('night');
        $amount_room = SessionUtil::getVar('amount_room');
        $total_price = SessionUtil::getVar('total_price');
        $booking = SessionUtil::getVar('booking');
*/
        $form = FormUtil::getPassedValue ('form', false );
        $card_exp_month = $this->_objData['card_exp_month'];
        $card_exp_year = substr($this->_objData['card_exp_year'], -2);
        $cardexpire = $card_exp_month.$card_exp_year;
        
        $chaincode = $this->_objData['chaincode'];
        
        $roomstays = $this->_objData['roomstays'];
          
        foreach($roomstays as $item) {
            //Gennerate next id
            $current_booking_id = DBUtil::selectFieldMax( 'pobbooking_booking', 'id', 'MAX', '');
            if($current_booking_id == null) {
                $current_booking_id = 1;
            }else {
                $current_booking_id = $current_booking_id+1;
            }
            $objects = array(
                    'id'                  => $current_booking_id,
                    'customer_refid'      => $refid,
                    'chaincode'           => $this->_objData['chaincode'],
                    'isocurrency'         => $this->_objData['isocurrency'],
                    'checkin_date'        => $item[startdate],
                    'checkout_date'       => $item[enddate],
                    'night'               => $item[night],
                    'amount_room'         => $item[numberofunit],
                    'roomtype'            => $item[invcode],
                    'adult'               => $item[adult],
                    'child'               => $item[child],
                    'room_rate'           => $item[rate],
                    'room_rate_total'     => $item[room_rate_total],
                    'identificational'    => $this->_objData['identificational'],
                    'nameprefix'          => $this->_objData['nameprefix'],
                    'givenname'           => $this->_objData['givenname'],
                    'surname'             => $this->_objData['surname'],
                    'addressline'         => $this->_objData['addressline'],
                    'cityname'            => $this->_objData['cityname'],
                    'stateprov'           => $this->_objData['stateprov'],
                    'countryname'         => $this->_objData['countryname'],
                    'postalcode'          => $this->_objData['postalcode'],
                    'mobile'              => $this->_objData['mobile'],
                    'phone'               => $this->_objData['phone'],
                    'email'               => $this->_objData['email'],
                    'addition_request'    => $this->_objData['addition_request'],
                    'cardcode'            => $this->_objData['cardcode'],
                    'cardnumber'          => $this->_objData['cardnumber'],
                    'cardholdername'      => $this->_objData['cardholdername'],
                    'cardexpire'          => $this->_objData['cardexpire']
            );
            
            DBUtil::insertObject($objects,'pobbooking_booking');

            //count room for each type
            

/*
            //update avalible booking room
            $current_avalible_booking = DBUtil::selectFieldMax( 'ihotel_roomtype', 'id', 'MAX', '');
            $pntables = pnDBGetTables();
            $column   = $pntables['ihotel_roomtype'];
            $obj = array('amount_booking' => '123 Some Street');
            $where    = "WHERE $column[name]='$item[roomtype]'";

            DBUTil::updateObject ($obj, 'customers', $where);
 */

        }



        //Call sendEmail method
        $this->sendEmail();


    }

    function updatePostProcess() {
        $form = FormUtil::getPassedValue ('form', false );
        $refid = $form['refid'];
        $status = $form['status_id'];
        if($refid) {
            $object = array('status_id' => $status);
            $where = "WHERE boo_refid = '$refid'";
            DBUtil::updateObject($object,'pobbooking_booking',$where);
        }
        if($refid) {
            $object = array('status_id' => $status);
            $where = "WHERE cus_refid = '$refid'";
            DBUtil::updateObject($object,'pobbooking_customer',$where);
        }


    }

/*
    function sendEmail() {
        //Get value from input
        $form = FormUtil::getPassedValue ('form', false);

        $booking = $this->_objData['roomstays'];



        //Config email
        $adminEmail = "admin@phuketcity.com";
        $toEmail = $form[email].",".$adminEmail;

        //Config email body
        $body = "Dear ".$this->_objData['nameprefix'] .' ' .$this->_objData['givenname'].",\n";
        $body.= "\tThank you for your enquiry. Your request as\n";
        $body.= "detailed below has been sent to one of our client\n";
        $body.= "service who will respond to you within 1-2 business day.\n";
        $body.= "Should you require any assistancen\n";
        $body.= "please contact us at info@phuketcity.com\n";
        $body.= "[BOOKING DETAILS]\n";
        $body.= "Request type: Make a booking\n";
        foreach($booking  as $item) {
            $body.= "           Room Type:".$item[invcode]."\n";
            $body.= "           Room:".$item[numberofunit]."\n";
            $body.= "           Night:".$item[night]."\n";
            $body.= "           Adult:".$item[adult]."\n";
            if($item[child]){
              $body.= "           Children :".$item[child]."\n";
            }
            $body.= "           Price / One room / One night:".$item[rate]."\n";
        }
        $body.= "Addition requests:".$form[addition_request]."\n";
        $body.= "[CUSTOMER INFORMATION]\n";
        $body.= "name : ".$form[nameprefix]."".$form[givenname]."\t".$form[surname]."\t\n";
        $body.= "email : ".$form[email]."\n";
        $body.= "[OTHERS]\n";
        foreach($booking  as $item) {
          $body.= "Checkin Date :".$item[startdate]."\n";
          $body.= "Checkout Date :".$item[enddate]."\n";
        }
        $body.= "--------BOOKING PRICE:".$form[totalall]." BAHT--------\n\n\n\n";
        $body.= "phuketcity.com Client Service\n";
        $body.= "info@phuketcity.com";
        $body.= "             ";

        mail($toEmail,$subject,$body,$header);
    }
*/
}