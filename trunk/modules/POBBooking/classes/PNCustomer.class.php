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
    $checkin_date = "2010-06-10";
    $checkout_date = "2010-06-13";
    $night = "2";
    $amount_room = "2";


    $booking[] = array(
                       "roomtype" => "Deluxe",
                       "bedtype" => "Twin",
                       "breakfast" => "yes",
                       "night" => "2",
                       "adult" => "2",
                       "child_age" => "1",
                       "price" => "5000"
                    );


    $booking[] = array(
                       "roomtype" => "Superior",
                       "bedtype" => "Single",
                       "breakfast" => "yes",
                       "night" => "2",
                       "adult" => "2",
                       "child_age" => "0",
                       "price" => "20000"

                    );


    SessionUtil::setVar('checkin_date', $checkin_date, '/', true, true);
    SessionUtil::setVar('checkout_date', $checkout_date, '/', true, true);
    SessionUtil::setVar('night', $night, '/', true, true);
    SessionUtil::setVar('amount_room', $amount_room, '/', true, true);

    SessionUtil::setVar('booking', $booking, '/', true, true);
    */
    /*
    return $is_validate;
  }
    */


    function insertPreProcess() {
        //get
        /*
    $card1 = FormUtil::getPassedValue ('form[card1]', false);
    $card2 = FormUtil::getPassedValue ('form[card2]', false);
    $card3 = FormUtil::getPassedValue ('form[card3]', false);
    $card4 = FormUtil::getPassedValue ('form[card4]', false);
        */
        $this->_objData['card_number'] = $this->_objData[card1].$this->_objData[card2].$this->_objData[card3].$this->_objData[card4];
        $this->_objData['status_id'] = 2;
        return true;
    }


    function insertPostProcess() {
        Loader::loadClass('DataUtilEx', "modules/Booking/pnincludes");

        $id = DBUtil::getInsertID ($this->_objType, $this->_objField);
        $refid = "HO00".$id;
        $object = array('refid'=>$refid);
        $where  = " cus_id = ".$id;
        DBUtil::updateObject($object,'pobbooking_customer',$where);

        if ($_POST['forward'] ) {
            $forward  = $_POST['forward'];
            //pnRedirect($list_url);
            //return true;
        }

        //Get session value
        $pre_checkin_date = SessionUtil::getVar('checkin_date');
        $pre_checkout_date = SessionUtil::getVar('checkout_date');
        $checkin_date = date('Y-m-d', strtotime($pre_checkin_date));
        $checkout_date = date('Y-m-d', strtotime($pre_checkout_date));
        $night = SessionUtil::getVar('night');
        $amount_room = SessionUtil::getVar('amount_room');
        $total_price = SessionUtil::getVar('total_price');
        $booking = SessionUtil::getVar('booking');

          
        foreach($booking as $item) {
            //Gennerate next id
            $current_booking_id = DBUtil::selectFieldMax( 'pobbooking_booking', 'id', 'MAX', '');
            if($current_booking_id == null) {
                $current_booking_id = 1;
            }else {
                $current_booking_id = $current_booking_id+1;
            }
            $objects = array(
                    'id'            => $current_booking_id,
                    'cus_id'        => $id,
                    'status_id'     => '2',
                    'refid'         => $refid,
                    'checkin_date'  => $checkin_date,
                    'checkout_date' => $checkout_date,
                    'night'         => $night,
                    'amount_room'   => $amount_room,
                    'roomtype'      => $item[roomtype],
                    'bedtype'       => $item[bedtype],
                    'breakfast'     => $item[breakfast],
                    'adult'         => $item[adult],
                    'child_age'     => $item[child_age],
                    'price'         => $item[price],
                    'total_price'   => $total_price
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

        //Delete session variable & data
        SessionUtil::delVar( 'checkin_date');
        SessionUtil::delVar( 'checkout_date');
        SessionUtil::delVar( 'night');
        SessionUtil::delVar( 'amount_room');
        SessionUtil::delVar( 'total_price');
        //SessionUtil::delVar( 'booking');

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


    function sendEmail() {
        //Get value from input
        $form = FormUtil::getPassedValue ('form', false);

        //Get session data
        $checkin_date = SessionUtil::getVar('checkin_date');
        $checkout_date = SessionUtil::getVar('checkout_date');
        $night = SessionUtil::getVar('night');
        $amount_room = SessionUtil::getVar('amount_room');
        $total_price = SessionUtil::getVar('total_price');
        $booking = SessionUtil::getVar('booking');


        //Config email
        $adminEmail = "info@karonphunaka.com";
        $toEmail = $form[email].",".$adminEmail;

        //Config email body
        $body = "Dear ".$form[titlename] .' ' .$form[firstname].",\n";
        $body.= "\tThank you for your enquiry. Your request as\n";
        $body.= "detailed below has been sent to one of our client\n";
        $body.= "service who will respond to you within 1-2 business day.\n";
        $body.= "Should you require any assistancen\n";
        $body.= "please contact us at info@karonphunaka.com\n";
        $body.= "[BOOKING DETAILS]\n";
        $body.= "Request type: Make a booking\n";
        foreach($booking  as $item) {
            $body.= "           Room Type:".$item[roomtype]."\n";
            $body.= "           Room:".$amount_room."\n";
            $body.= "           Night:".$item[night]."\n";
            $body.= "           Adult:".$item[adult]."\n";
            $body.= "           Children age:".$item[child_age]."\n";
            $body.= "           Price/ One room:".$item[price]."\n";
        }
        $body.= "Addition requests:".$form[addition_request]."\n";
        $body.= "[CUSTOMER INFORMATION]\n";
        $body.= "name : ".$form[titlename]."".$form[firstname]."\t".$form[lastname]."\t\n";
        $body.= "email : ".$form[email]."\n";
        $body.= "[OTHERS]\n";
        $body.= "Checkin Date :".$checkin_date."\n";
        $body.= "Checkout Date :".$checkout_date."\n";
        $body.= "--------BOOKING PRICE:".$total_price." BAHT--------\n\n\n\n";
        $body.= "karonphunaka.com Client Service\n";
        $body.= "info@karonphunaka.com";
        $body.= "             ";

        mail($toEmail,$subject,$body,$header);
    }

}