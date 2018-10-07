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
    const ONLY_USE_TOKEN = 'Only use rows where';

    const DESCENDING_KEY = 'descending';
    const LESS_THAN_KEY = '<';
    const GREATER_THAN_KEY = '>';
    const LESS_THAN_OR_EQUAL_KEY = '>=';
    const GREATER_THAN_OR_EQUAL_KEY = '<=';
    const IN_KEY = 'IN';
    const NOT_IN_KEY = 'NOT IN';

    protected $filter_column;
    protected $filter_value;
    protected $filter_type;
    protected $is_filter_array;
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
        if(isset($this->data['colours'])) {
            ksort($this->data['colours']);
        }

        if ($this->order === Chart::DESCENDING_KEY) {
            $this->data['x_values'] = array_reverse($this->data['x_values']);
            $this->data['y_values'] = array_reverse($this->data['y_values']);
            if(isset($this->data['colours'])) {
                $this->data['colours'] = array_reverse($this->data['colours']);
            }
        }
    }

    protected function sortByY() {
        if ($this->order === Chart::DESCENDING_KEY) {
            array_multisort($this->data['y_values'], SORT_DESC ,$this->data['x_values']);
        } else {
            array_multisort($this->data['y_values'], $this->data['x_values']);
        }

    }
}