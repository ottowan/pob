<?php
class MySQLUtil {
  

  public static function validateDataType($dataType){
    switch (strtolower($dataType)) {
//        case "string":
//            $sqlType = "VARCHAR(255) ";
//            break;
        case "string":
            $sqlType = "TEXT ";
            break;
        case "int":
            $sqlType = "INT(11) ";
            break;
        case "double":
            $sqlType = "DOUBLE ";
            break;
        case "text":
            $sqlType = "TEXT ";
            break;
        case "date":
            $sqlType = "DATE ";
            break;
        case "datetime":
            $sqlType = "DATETIME ";
            break;
        case "float":
            $sqlType = "FLOAT ";
            break;
        case "timestamp":
            $sqlType = "TIMESTAMP ";
            break;
        case "boolean":
            $sqlType = "BOOLEAN ";
            break;
        default :
          $sqlType = "INT(11) ";
          break;
    }

    return $sqlType;
  }

}

?>