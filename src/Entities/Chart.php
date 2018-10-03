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

    const DESCENDING_KEY = 'descending';

    protected $order;
    protected $data = [];

    public function getData() {
        return $this->data;
    }

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

    protected function sortByX() {
        ksort($this->data['x_values']);
        ksort($this->data['y_values']);
        ksort($this->data['colours']);

        if ($this->order === Chart::DESCENDING_KEY) {
            $this->data['x_values'] = array_reverse($this->data['x_values']);
            $this->data['y_values'] = array_reverse($this->data['y_values']);
            $this->data['colours'] = array_reverse($this->data['colours']);
        }
    }
}