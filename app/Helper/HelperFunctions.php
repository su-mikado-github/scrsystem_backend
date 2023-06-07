<?php
/* ひな型
if (function_exists('x')) {
    function x() {
        //
    }
}
*/
if (!function_exists('op')) {
    function op($value) {
        return optional($value);
    }
}

