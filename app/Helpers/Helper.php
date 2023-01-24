<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Helper {

    //function for back same page after update,delete,cancel
    public static function queryPageStr($qpArr) {
        //link for same page after query
        $qpStr = '';
        if (!empty($qpArr)) {
            $qpStr .= '?';
            foreach ($qpArr as $key => $value) {
                if ($value != '') {
                    $qpStr .= $key . '=' . $value . '&';
                }
            }
            $qpStr = trim($qpStr, '&');
            return $qpStr;
        }
    }

    public static function numberformat($num = 0, $digit = 3) {
        return number_format($num, $digit, '.', ',');
    }

   
    public static function dateFormat($date = '0000-00-00') {
        return date('d/m/Y', strtotime($date));
    }

    public static function formatDate($dateTime = '0000-00-00 00:00:00') {
        return date('d F Y', strtotime($dateTime));
    }

    public static function formatDateTime($dateTime = '0000-00-00 00:00:00') {
        return date('d F Y h:i A', strtotime($dateTime));
    }

    public static function arrayToString($array = []) {
        $string = '';
        if (!empty($array)) {
            $string = implode(',', $array);
        }
        return $string;
    }

    public static function stringToArray($string = null) {
        $array = [];
        if (!empty($string)) {
            $array = explode(',', $string);
        }
        return $array;
    }

    //new function
    public static function dateFormatConvert($date = '0000-00-00') {
        return date('Y-m-d', strtotime($date));
    }

    public static function numberFormat2Digit($num = 0) {
        if (empty($num)) {
            $num = 0;
        }
        return number_format($num, 2, '.', ',');
    }

    public static function numberFormatDigit2($num = 0) {
        if (empty($num)) {
            $num = 0;
        }
        return number_format($num, 2, '.', '');
    }

    public static function daySpan($timeSpan) {
        $days = ' Day';
        if ($timeSpan > 1) {
            $days = ' Days';
        }
        return $days;
    }

    public static function trimString($string) {
        $dot = strlen($string) > 20 ? '...' : '';
        $returnString = substr($string, 0, 20) . $dot;
        return $returnString;
    }

    public static function numberToWord($number = null) {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . self::numberToWord(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::numberToWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::numberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::numberToWord($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public static function dateTimeFormatConvert($date = '0000-00-00') {
        return date('Y-m-d H:i:s', strtotime($date));
    }

    public static function getUrlRequestText($url) {
        $urlTextArr = explode("?", $url);
        $urlRequestText = !empty($urlTextArr[1]) ? '?' . $urlTextArr[1] : '';
        return $urlRequestText;
    }

    public static function numberToOrdinal($number = null) {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (!empty($number)) {
            if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
                return $number . 'th';
            } else {
                return $number . $ends[$number % 10];
            }
        } else {
            return '';
        }
    }

    public static function dateDiff($startDate, $endDate) {
        $startDateTime = date_create($startDate);
        $endDateTime = date_create($endDate);

        $interval = date_diff($startDateTime, $endDateTime);

        $format = '';
        if (!empty($interval)) {
            if (!empty($interval->y)) {
                if ($interval->y > 1) {
                    if (!empty($interval->m)) {
                        if ($interval->m > 1) {
                            if (!empty($interval->d)) {
                                if ($interval->d > 1) {
                                    $format = '%y Years %m Months %d Days';
                                } else {
                                    $format = '%y Years %m Months %d Day';
                                }
                            } else {
                                $format = '%y Years %m Months';
                            }
                        } else {
                            if (!empty($interval->d)) {
                                if ($interval->d > 1) {
                                    $format = '%y Years %m Month %d Days';
                                } else {
                                    $format = '%y Years %m Month %d Day';
                                }
                            } else {
                                $format = '%y Years %m Month';
                            }
                        }
                    } else {
                        if (!empty($interval->d)) {
                            if ($interval->d > 1) {
                                $format = '%y Years %d Days';
                            } else {
                                $format = '%y Years %d Day';
                            }
                        } else {
                            $format = '%y Years';
                        }
                    }
                } else {
                    if (!empty($interval->m)) {
                        if ($interval->m > 1) {
                            if (!empty($interval->d)) {
                                if ($interval->d > 1) {
                                    $format = '%y Year %m Months %d Days';
                                } else {
                                    $format = '%y Year %m Months %d Day';
                                }
                            } else {
                                $format = '%y Year %m Months';
                            }
                        } else {
                            if (!empty($interval->d)) {
                                if ($interval->d > 1) {
                                    $format = '%y Year %m Month %d Days';
                                } else {
                                    $format = '%y Year %m Month %d Day';
                                }
                            } else {
                                $format = '%y Year %m Month';
                            }
                        }
                    } else {
                        if (!empty($interval->d)) {
                            if ($interval->d > 1) {
                                $format = '%y Year %d Days';
                            } else {
                                $format = '%y Year %d Day';
                            }
                        } else {
                            $format = '%y Year';
                        }
                    }
                }
            } else {
                if (!empty($interval->m)) {
                    if ($interval->m > 1) {
                        if (!empty($interval->d)) {
                            if ($interval->d > 1) {
                                $format = '%m Months %d Days';
                            } else {
                                $format = '%m Months %d Day';
                            }
                        } else {
                            $format = '%m Months';
                        }
                    } else {
                        if (!empty($interval->d)) {
                            if ($interval->d > 1) {
                                $format = '%m Month %d Days';
                            } else {
                                $format = '%m Month %d Day';
                            }
                        } else {
                            $format = '%m Month';
                        }
                    }
                } else {
                    if (!empty($interval->d)) {
                        if ($interval->d > 1) {
                            $format = '%d Days';
                        } else {
                            $format = '%d Day';
                        }
                    } else {
                        $format = '%d Day';
                    }
                }
            }
        }
        return $interval->format($format);
    }

    // bakibillah

    public static function formatDateTimeForPost($dateTime = '0000-00-00 00:00:00') {

        return date('  j F  Y h:i A ', strtotime($dateTime));
    }

    public static function limitTextWords($content = false, $limit = false, $url = false) {
        $stripTags = false;
        $ellipsis = true;
        if ($content && $limit) {
            $content = ($stripTags ? strip_tags($content) : $content);
            $content = explode(' ', $content, $limit + 1);
            if ($limit > sizeof($content)) {
                $ellipsis = false;
            }
            if ($ellipsis) {
                array_pop($content);
                if ($url) {
                    $url = $url;
                } else {
                    $url = '#';
                }
                array_push($content, '<span class="rm">...<a href="' . $url . '" class="read-more"> read more</a></span>');
            }
            $content = implode(' ', $content);
        }
        return $content;
    }

    public static function nextPost($table, $order) {
        $next = DB::table($table)->where('order', '>', $order)->where('status_id', 1)->orderBy('order', 'ASC')->first();

        return $next;
    }

    public static function prevPost($table, $order) {
        $next = DB::table($table)->where('order', '<', $order)->where('status_id', 1)->orderBy('order', 'DESC')->first();

        return $next;
    }
    public static function generateSlug($name){
        $pattern = [
            '/', '|', '\\', '//', '?', ',', '-', '_', ' ', "'", '"'
            , '!', '@', '#', '$', '%', '^', '*', ';'
            , ':', '(', ')', '[', ']', '{', '}'
        ];
        return !empty($name) ? str_replace($pattern, '-', strtolower($name)) : '';
    }
}
