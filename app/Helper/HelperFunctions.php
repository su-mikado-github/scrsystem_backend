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

if (!function_exists('hhmm_to_mins')) {
    function hhmm_to_mins($hhmm, $default=null) {
        if (isset($hhmm)) {
            list($h, $m) = explode(':', $hhmm);
            return intval($h) * 60 + intval($m);
        }
        return $default;
    }
}

if (!function_exists('mins_to_hhmm')) {
    function mins_to_hhmm($mins, $default=null) {
        return (is_int($mins) ? sprintf('%02d:%02d', floor($mins / 60), $mins % 60) : $default);
    }
}

if (!function_exists('time_to_mins')) {
    function time_to_mins($time, $default=null) {
        if (isset($time)) {
            list($h, $m, $s) = explode(':', $time);
            return intval($h) * 60 + intval($m);
        }
        return $default;
    }
}

if (!function_exists('mins_to_time')) {
    function mins_to_time($mins, $default=null) {
        return (is_int($mins) ? sprintf('%02d:%02d:00', floor($mins / 60), $mins % 60) : $default);
    }
}

if (!function_exists('hhmm_to_time')) {
    function hhmm_to_time($hhmm, $default=null) {
        if (isset($hhmm)) {
            list($h, $m) = explode(':', $hhmm);
            return sprintf('%02d:%02d:00', intval($h), intval($m));
        }
        return $default;
    }
}

if (!function_exists('time_to_hhmm')) {
    function time_to_hhmm($time, $default=null) {
        if (isset($time)) {
            list($h, $m, $s) = explode(':', $time);
            return sprintf('%02d:%02d', intval($h), intval($m));
        }
        return $default;
    }
}
