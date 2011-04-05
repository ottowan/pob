<?php
  function smarty_function_selector_object_array_ex ($params, &$smarty)
  {
      $selectedValue  = (isset($params['selectedValue'])  ? $params['selectedValue']  : 0);
      $defaultValue   = (isset($params['defaultValue'])   ? $params['defaultValue']   : 0);
      $defaultText    = (isset($params['defaultText'])    ? $params['defaultText']    : '');
      $allValue       = (isset($params['allValue'])       ? $params['allValue']       : 0);
      $allText        = (isset($params['allText'])        ? $params['allText']        : '');
      $field          = (isset($params['field'])          ? $params['field']          : 'id');
      $displayField   = (isset($params['displayField'])   ? $params['displayField']   : 'name');
      $displayField2  = (isset($params['displayField2'])  ? $params['displayField2']  : '');
      $fieldSeparator = (isset($params['fieldSeparator']) ? $params['fieldSeparator'] : ', ');
      $name           = (isset($params['name'])           ? $params['name']           : 'selector');
      $class          = (isset($params['class'])          ? $params['class']          : '');
      $where          = (isset($params['where'])          ? $params['where']          : '');
      $sort           = (isset($params['sort'])           ? $params['sort']           : '');
      $modname        = (isset($params['modname'])        ? $params['modname']        : '');
      $submit         = (isset($params['submit'])         ? $params['submit']         : false);
      $disabled       = (isset($params['disabled'])       ? $params['disabled']       : false);
      $multipleSize   = (isset($params['multipleSize'])   ? $params['multipleSize']   : 1);
      $onChange       = (isset($params['onChange'])       ? $params['onChange']   : false);
      $onClick        = (isset($params['onClick'])        ? $params['onClick']   : false);
      $style          = (isset($params['style'])          ? $params['style']   : false);
      $size           = (isset($params['size'])           ? $params['size']   : false);
      $queryField     = (isset($params['queryField'])     ? $params['queryField']   : false);
      $queryValue     = (isset($params['queryValue'])     ? $params['queryValue']   : false);

      // get all but force execution of new query for object get
      if (!$where) {
        $where       = (empty($queryField) && empty($queryValue)) ? ' ' : $queryField.'='.$queryValue;
      }else{
        $where       = (empty($queryField) && empty($queryValue)) ? $where : $queryField.'='.$queryValue;
      }
      Loader::loadClass('HtmlUtilEx','modules/Serialize/pnincludes');
      return HtmlUtilEx::getSelector_ObjectArray ($modname, $class, $name, $field, $displayField, $where, $sort,
                                                $selectedValue, $defaultValue, $defaultText, $allValue, $allText,
                                                $displayField2, $submit, $disabled, $fieldSeparator, $multipleSize,
                                                $onChange, $onClick, $style, $size, $queryField, $queryValue);
  }