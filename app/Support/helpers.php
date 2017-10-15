<?php

if (! function_exists('get_image_url')) {
    function get_image_url($prefix, $size = 'original') {

        if (!$prefix) {
            return;
        }

        if (app('url')->isValidUrl($prefix)) {
            return $prefix;
        }
        if(str_contains($prefix, 'http://'))
        {
            return $prefix;
        }
        if(str_contains($prefix, 'https://'))
        {
            return $prefix;
        }
        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        if (!in_array($size, $sizes)) {
            $size = 'original';
        }

        return 'http://ucontent.icheck.vn/' . $prefix . '_' . $size . '.jpg';
    }
}


function getAttr($id) {
    $attr = \App\Models\Icheck\Product\ProductAttr::find($id);
    return $attr;

}

function validate_barcode($barcode) {
    // check to see if barcode is 13 digits long
    if (!preg_match("/^[0-9]{13}$/", $barcode)) {
        return false;
    }

    $digits = $barcode;

    // 1. Add the values of the digits in the
    // even-numbered positions: 2, 4, 6, etc.
    $even_sum = $digits[1] + $digits[3] + $digits[5] +
                $digits[7] + $digits[9] + $digits[11];

    // 2. Multiply this result by 3.
    $even_sum_three = $even_sum * 3;

    // 3. Add the values of the digits in the
    // odd-numbered positions: 1, 3, 5, etc.
    $odd_sum = $digits[0] + $digits[2] + $digits[4] +
               $digits[6] + $digits[8] + $digits[10];

    // 4. Sum the results of steps 2 and 3.
    $total_sum = $even_sum_three + $odd_sum;

    // 5. The check character is the smallest number which,
    // when added to the result in step 4, produces a multiple of 10.
    $next_ten = (ceil($total_sum / 10)) * 10;
    $check_digit = $next_ten - $total_sum;

    // if the check digit and the last digit of the
    // barcode are OK return true;
    if ($check_digit == $digits[12]) {
        return true;
    }

    return false;
}

function validate_EAN13Barcode($barcode)
{
    // check to see if barcode is 13 digits long
    if (!preg_match("/^[0-9]{13}$/", $barcode)) {
        return false;
    }

    $digits = $barcode;

    // 1. Add the values of the digits in the
    // even-numbered positions: 2, 4, 6, etc.
    $even_sum = $digits[1] + $digits[3] + $digits[5] +
                $digits[7] + $digits[9] + $digits[11];

    // 2. Multiply this result by 3.
    $even_sum_three = $even_sum * 3;

    // 3. Add the values of the digits in the
    // odd-numbered positions: 1, 3, 5, etc.
    $odd_sum = $digits[0] + $digits[2] + $digits[4] +
               $digits[6] + $digits[8] + $digits[10];

    // 4. Sum the results of steps 2 and 3.
    $total_sum = $even_sum_three + $odd_sum;

    // 5. The check character is the smallest number which,
    // when added to the result in step 4, produces a multiple of 10.
    $next_ten = (ceil($total_sum / 10)) * 10;
    $check_digit = $next_ten - $total_sum;

    // if the check digit and the last digit of the
    // barcode are OK return true;
    if ($check_digit == $digits[12]) {
        return true;
    }

    return false;
}


 function _make_url_clickable_cb($matches)
{
    $ret = '';
    $url = $matches[2];

    if (empty($url))
        return $matches[0];
    // removed trailing [.,;:] from URL
    if (in_array(substr($url, -1), array('.', ',', ';', ':')) === true) {
        $ret = substr($url, -1);
        $url = substr($url, 0, strlen($url) - 1);
    }
    return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
}

 function _make_web_ftp_clickable_cb($matches)
{
    $ret = '';
    $dest = $matches[2];
    $dest = 'http://' . $dest;

    if (empty($dest))
        return $matches[0];
    // removed trailing [,;:] from URL
    if (in_array(substr($dest, -1), array('.', ',', ';', ':')) === true) {
        $ret = substr($dest, -1);
        $dest = substr($dest, 0, strlen($dest) - 1);
    }
    return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
}

 function _make_email_clickable_cb($matches)
{
    $email = $matches[2] . '@' . $matches[3];
    return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
}

function convertTextToLink($ret)
{

    $ret = ' ' . $ret;
    // in testing, using arrays here was found to be faster
    $ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
    $ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
    $ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);

    // this one is not in an array because we need it to run last, for cleanup of accidental links within links
    $ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
    $ret = trim($ret);
    return $ret;
}

function fakeData($value){
    $now = Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::createFromDate(2016,10,1)->startOfDay());
    $day2 = \Carbon\Carbon::createFromDate(2016,9,31)->startOfDay()->diffInDays(\Carbon\Carbon::createFromDate(2015,07,20)->startOfDay());
    $new = $value + round($value/$now)*($day2);
    return $new;
}
