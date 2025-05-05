<?php

    if(! function_exists('formarPrice')){
        /**
         * formatPrice
         * 
         * @param mixed $str
         * @return void
         */
        function formatPrice($str) {
            return 'Rp. ' . number_format($str, '0', '', '.');
        }
    }