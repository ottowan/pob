<?php
class HtmlUtilEx {

    function getSelector_ObjectArray ($modname, $objectType, $name, $field='id', $displayField='name',
                                      $where='', $sort='', $selectedValue='', $defaultValue=0, $defaultText='', $allValue=0, $allText='',
                                      $displayField2=null, $submit=true, $disabled=false, $fieldSeparator=', ', $multipleSize=1,
                                      $onchange='', $onclick='', $style, $size, $queryField, $queryValue)
    {
        if (!$modname) {
            return pn_exit ('Invalid modname passed to HtmlUtil::getSelector_ObjectArray ...');
        }

        if (!$objectType) {
            return pn_exit ('Invalid object name passed to HtmlUtil::getSelector_ObjectArray ...');
        }

        if (!pnModDBInfoLoad($modname)) {
            return "Unavailable/Invalid modulename [$modname] passed to HtmlUtil::getSelector_ObjectArray";
        }

        $id = strtr ($name, '[]', '__');
        $disabled     = $disabled ? 'disabled="disabled"' : '';
        $multiple     = $multipleSize > 1 ? 'multiple="multiple"' : '';
        $multipleSize = $multipleSize > 1 ? "size=\"$multipleSize\"" : '';
        $submit       = $submit ? 'onchange="this.form.submit();"' : '';
        $onchange     = empty($onchange) ? '' : "onchange='" . $onchange . "'";
        $onclick      = empty($onclick) ? '' : "onclick='" . $onclick . "'";
        $style        = empty($style) ? '' : 'style="'.$style.'"';
        $size         = empty($size) ? '' : 'size="'.$size.'"';
        $event        = empty($onchange) ? $onclick : $onchange;
        $html = "<select name=\"$name\" id=\"$id\" $multipleSize $multiple $submit $event $disabled $style $size>";

        if ($defaultText && !$selectedValue) {
            $sel = ((string)$defaultValue==(string)$selectedValue ? 'selected="selected"' : '');
            $html .= "<option value=\"$defaultValue\" $sel>$defaultText</option>";
        }

        if ($allText) {
            $sel = ((string)$allValue==(string)$selectedValue ? 'selected="selected"' : '');
            $html .= "<option value=\"$allValue\" $sel>$allText</option>";
        }

        if (!SecurityUtil::checkPermission("$objectType::", '::', ACCESS_OVERVIEW)) {
            return "Security Check failed for modulename [$modname] passed to HtmlUtil::getSelector_ObjectArray";
        }

        $classname = Loader::loadClassFromModule ($modname, $objectType, true);
        if (!$classname) {
            return "Unable to load class [$objectType] for module [$modname]";
        }

        $class = new $classname ();
        $dataarray = $class->get ($where, $sort, -1, -1, '', false/*, $distinct*/);

        foreach ($dataarray as $object) {
            $val   = $object[$field];
            $sel   = ((string)$val==(string)$selectedValue ? 'selected="selected"' : '');
            $disp  = $object[$displayField];

            $disp2 = '';
            if ($displayField2) {
                $disp2 = $fieldSeparator . $object[$displayField2];
            }

            $html .= "<option value=\"$val\" $sel>$disp $disp2</option>";
        }

        $html .= '</select>';
        return $html;
    }

}