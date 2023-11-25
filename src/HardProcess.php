<?php

namespace GetMarks;

class HardProcess
{


    public static  function getDetails($greeting, $quantity, $product, $multiplier, $receiver)
    {
        echo "Hello " . $greeting . " " .  ($quantity * $multiplier) . " " . $product . " for " . " my " . $receiver;
    }
}
