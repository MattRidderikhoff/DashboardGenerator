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
    const DATASET_TOKEN = 'Dataset is';
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
    const EQUAL_KEY = '=';
    const INCLUDE_KEY = 'include';
    const EXCLUDE_KEY = 'exclude';
    const SCALE_KEY = 'Scale by';

    protected $data = [];
    protected $dataset_id;

    protected $order;
    protected $filter_column;
    protected $filter_value;
    protected $filter_type;
    protected $scale_by;

    public function getDatasetId() {
        return $this->dataset_id;
    }

    public function getData() {
        if (isset($this->scale_by)) {$this->scaleY();}
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

    protected function scaleY() {
        $new_y_data = array_map(function ($n) { return ($n * $this->scale_by);}, $this->data['y_values']);
        $this->data['y_values'] = $new_y_data;
    }

    protected function separateFilter(TokenManager $token_manager) {
        $this->filter_column = $token_manager->getNextToken();
        $this->filter_type = $token_manager->getNextToken();

        if ($this->filter_type === Chart::INCLUDE_KEY || $this->filter_type === Chart::EXCLUDE_KEY) {
            $filter_string = $token_manager->getNextToken();
            $filter_string = str_replace('(', '', $filter_string);
            $filter_string = str_replace(')', '', $filter_string);

            $filter_values = explode(',', $filter_string);
            foreach ($filter_values as $filter_value) {
                $this->filter_value[] = trim($filter_value);
            }
        } else {
            $this->filter_value = $token_manager->getNextToken();
        }
    }

    protected function passesFilter($row) {

        if (is_array($this->filter_value)) {
            switch ($this->filter_type) {
                case self::INCLUDE_KEY:
                    return (in_array(trim($row[$this->filter_column]), $this->filter_value));
                    break;
                case self::EXCLUDE_KEY:
                    return (!in_array(trim($row[$this->filter_column]), $this->filter_value));
                    break;
                default:
                    throw new \Exception('Invalid comparison operator');
                    break;
            }
        } else {
            switch ($this->filter_type) {
                case self::LESS_THAN_KEY:
                    return (intval(trim($row[$this->filter_column])) < $this->filter_value);
                case self::LESS_THAN_OR_EQUAL_KEY:
                    return (intval(trim($row[$this->filter_column])) <= $this->filter_value);
                case self::GREATER_THAN_KEY:
                    return (intval(trim($row[$this->filter_column])) > $this->filter_value);
                case self::GREATER_THAN_OR_EQUAL_KEY:
                    return (intval(trim($row[$this->filter_column])) >= $this->filter_value);
                case self::EQUAL_KEY:
                    return (intval(trim($row[$this->filter_column])) == $this->filter_value);
                case null:
                    return true;
                default:
                    throw new \Exception('Invalid comparison operator');
                    break;
            }
        }
    }
}