<?php
class DateUtilEx extends DateUtil{
    /**
     * Return a formatted datetime for the given timestamp (or for now)
     *
     * @param time      The timestamp (string) which we wish to format (default==now)
     * @param format    The format to use when formatting the date (optional)
     *
     * @return datetime The datetime formatted according to the specified format
     */
    function getDatetime ($time='', $format=DATEFORMAT_FIXED)
    {
        switch(trim(strtolower($format))){
            case 'datelong':
                $format = _DATELONG;
                break;
            case 'datebrief':
                $format = _DATEBRIEF;
                break;
            case 'datestring':
                $format = _DATESTRING;
                break;
            case 'datestring2':
                $format = _DATESTRING2;
                break;
            case 'datetimebrief':
                $format = _DATETIMEBRIEF;
                break;
            case 'datetimelong':
                $format = _DATETIMELONG;
                break;
            case 'timebrief':
                $format = _TIMEBRIEF;
                break;
            case 'timelong':
                $format = _TIMELONG;
                break;
            default:
        } // switch

        // bp 2008-03-29 - translation of day and month names if Windows utf-8 system is used
        if ($time) {
            $dtstr = strftime($format, $time);
        } else {
            $dtstr = strftime($format);
        }
        $encoding = pnSessionGetVar('encoding');
        $cs = strtolower(_CHARSET);
        $cs == 'utf-8' ? $cs = 'utf8' : '';	
        // applies only if $encoding is set, iconv is available and we're using utf8
        if ((!empty($encoding)) && ($cs == 'utf8')) {
            // check for iconv - not included in std. win distribution
            if (!extension_loaded('iconv')) {
                if (!dl('iconv.so')) {
                    return $dtstr;
                }
            }	
            if ($encoding !=  $cs) {
                $date = iconv($encoding, $cs, $dtstr);
                if ($date){
                  $dtstr = $date;
                }
            }
        }
        return $dtstr;
    }
    /*
    * transform date for store in db
    * ex.
    * 2551-10-12 => 2008-10-12
    * 01-20-2551 => 2008-20-01
    * 01/20/2551 => 2008-20-01
    *
    * @param string $date the date string
    */
    function transformDate($date){
      $date = trim($date);
      $t = strpos($date,'T');
      $split = array();
      if ($t){
        $date = substr($date,0,$t);
        $split= array_reverse(preg_split("/[.\-\/]/",$date));
      }else{
        $split = preg_split("/[.\-\/]/",$date);
      }
      //$split = explode('/',$date);
      $dd = 0;
      $mm = 0;
      $yyyy = 0;
      if (count($split) >= 3) {
        if (intval($split[0]) > 1000){
          $split = array_reverse($split);
        }
        $dd = (int)$split[0];
        $mm = (int)$split[1];
        $yyyy = (int)$split[2];
        //check  year value
        if ( ($yyyy >= 1500) && ($yyyy <= (int)date('Y') + 1000)){

          //check is zero
          $dd = ($dd === 0)?1:$dd;
          $mm = ($mm === 0)?1:$mm;
          
          $dd = ((int)$dd > 31)?1:$dd;
          $mm = ((int)$mm > 12)?1:$mm;
          
          $dd = (strlen($dd) === 1)?'0'.$dd:$dd;
          $mm = (strlen($mm) === 1)?'0'.$mm:$mm;
          //covert thai to utc
          if ((int)$yyyy > date('Y') + 300){
            $yyyy = ($yyyy >= date('Y'))?$yyyy-543:$yyyy;
          }    
          return $yyyy .'-' . $mm . '-' . $dd;
        }else{
          return false;
        }
      }
      return false;
      
    }
}
