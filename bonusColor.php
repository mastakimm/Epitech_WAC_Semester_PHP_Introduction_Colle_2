<?php

$str = $argv[1];
$displayLen = $argv[2];
$txtColor = $argv[3];
$bgColor = $argv[4];
$styleTxt = $argv[5];

displayCycle($str, $displayLen, $txtColor, $bgColor, $styleTxt);
function displayCycle($str, $displayLen, $txtColor, $bgColor, $styleTxt)
{
    $str = shColorText($str, $txtColor, $bgColor, $styleTxt);
    $str = parseShColorTag($str);
    $temp = str_repeat(" ", $displayLen) . $str;
    $strLen = strlen($temp);

    while (true) {
        for ($i = 0; $i < $strLen - $displayLen; $i++) {
            echo substr($temp, $i, $displayLen);
            usleep(500000);  // Attendre 0.5 seconde entre chaque char.
            system('clear');
        }
    }
}

function shColorText($str, $txtColor, $bgColor, $styleTxt)
{
    $__ESC = "\033";
    $__START = "[";
    $__END = "m";
    $__CLEAR = $__ESC . "[2J";
    $__NORMAL = $__ESC . "[0m";
    if ($str === 'CLEAR') {
        return $__NORMAL . $__CLEAR;
    }
    if (empty($str) || !$str) {
        return $__NORMAL;
    }
    //Text color
    $aTextColor['black'] = 30;
    $aTextColor['red'] = 31;
    $aTextColor['green'] = 32;
    $aTextColor['yellow'] = 33;
    $aTextColor['blue'] = 34;
    $aTextColor['magenta'] = 35;
    $aTextColor['cyan'] = 36;
    $aTextColor['white'] = 37;

    //Background color
    $aBgColor['black'] = 40;
    $aBgColor['red'] = 41;
    $aBgColor['green'] = 42;
    $aBgColor['yellow'] = 43;
    $aBgColor['blue'] = 44;
    $aBgColor['magenta'] = 45;
    $aBgColor['cyan'] = 46;
    $aBgColor['white'] = 47;

    //style text
    $aStyle['none'] = 0; //normal
    $aStyle['bold'] = 1; //gras
    $aStyle['underline'] = 4; //souligné
    $aStyle['flashing'] = 5; //clignotant
    $aStyle['reverse'] = 7; //inversé

    $c = $__ESC . $__START;
    if ($styleTxt && isset($aStyle[$styleTxt])) {
        $a[] = $aStyle[$styleTxt];
    }
    if ($txtColor && isset($aTextColor[$txtColor])) {
        $a[] = $aTextColor[$txtColor];
    }
    if ($bgColor && isset($aBgColor[$bgColor])) {
        $a[] = $aBgColor[$bgColor];
    }
    if (!is_array($a)) {
        return $str;
    }
    $c = $__ESC . $__START . join(';', $a) . $__END;

    return $c . $str . $__NORMAL;
}

function parseShColorTag($str)
{

    $tag = "/(<c[^>]*>)([^<]*)<\/c>/";
    $innerTag = "/([\w]+)=([\w]+)/";
    preg_match_all($tag, $str, $r);

    if (!is_array($r[1])) {
        return $str;
    }
    foreach ($r[1] as $k => $v) {
        preg_match_all($innerTag, $v, $r2);
        if (!is_array($r2[1])) {
            return $str;
        }
        $c = $bg = $s = false;
        while (list($i,$value) = each($r2[1])) {
            switch ($value) {
                case 'c':
                    $c = $r2[2][$i];
                    break;

                case 'bg':
                    $bg = $r2[2][$i];
                    break;

                case 's':
                    $s = $r2[2][$i];
                    break;
            }
        }
    }

    $string = shColorText($r[2][$k], $c, $bg, $s);

    $str = str_replace($r[0][$k], $string, $str);

    return $str;
}
