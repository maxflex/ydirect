<?php
    /**
     * Egerep connection helper
     */
    function egecrm($table) {
        return \DB::connection('egecrm')->table($table);
    }

    function cleanNumber($number)
    {
        $number = preg_replace("/[^0-9]/", "", $number);
        if ($number && $number[0] != '7') {
            $number = '7' . $number;
        }
        return $number;
    }
