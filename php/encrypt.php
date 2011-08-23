<?php 
  $source = "hello";
  $fp=fopen("./pob.public.pem","r"); 
  $pub_key=fread($fp,8192); 
  fclose($fp); 
  $key_resource = openssl_get_publickey($pub_key); 
  if (!$key_resource) {
      echo "Cannot get public key";
  }

  openssl_public_encrypt($source,$crypttext, $key_resource ); 

  if (!empty($crypttext)) {
      openssl_free_key($key_resource);
      echo base64_encode($crypttext); 
      //echo "Encryption OK!";
  }else{
      echo "Cannot Encrypt";
  }

  /*uses the already existing key resource*/ 



?>