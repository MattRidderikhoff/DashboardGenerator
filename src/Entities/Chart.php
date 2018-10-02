<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:46 PM
 */

namespace App\Entities;


abstract class Chart extends Node
{
    const X_AXIS_TOKEN = 'X is';
    const Y_AXIS_TOKEN = 'Y is';
    const X_ORDER_TOKEN = 'Order X';
    const Y_ORDER_TOKEN = 'Order Y';

    protected $data = [];

    protected function getRandomColour() {
        $letters = str_split('0123456789ABCDEF');
        $colour = '#';
        for ($i = 0; $i < 6; $i++) {
            $colour .= $letters[random_int(0, 15)];
        }
        return $colour;
    }

    protected function getNewColour($existing_colours) {
        $random_colour = $this->getRandomColour();
        while (in_array($random_colour, $existing_colours)) {
            $random_colour = $this->getRandomColour();
        }
        return $random_colour;
    }
}