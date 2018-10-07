<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:40 PM
 */

namespace App\Entities;

class LineChart extends Chart
{
    private $x_axis;
    private $y_axis;
    private $x_order;
    private $y_order;

    public function __construct()
    {
        $this->type = Node::TYPE_LINE_CHART;
    }

    public function evaluate($dataset)
    {
        $data = [];
        foreach ($dataset as $row) {
            if ($this->passesFilter($row)) {

                $x_value = trim($row[$this->x_axis]);
                if (!isset($data[$x_value])) {
                    $data[$x_value]['x_value'] = $x_value;
                    $data[$x_value]['y_value'] = 0;
                }

                $data[$x_value]['y_value'] += intval($row[$this->y_axis]);
            }
        }

        foreach ($data as $column) {
            $x_value = $column['x_value'];
            $this->data['x_values'][$x_value] = $x_value;
            $this->data['y_values'][$x_value] = $column['y_value'];
        }

        if (isset($this->x_order)) {
            $this->order = $this->x_order;
            $this->sortByX();
        } else if (isset($this->y_order)) {
            $this->order = $this->y_order;
            $this->sortByY();
        }
    }

    public function addAttribute(TokenManager $token_manager, $token)
    {
        switch ($token) {
            case self::X_AXIS_TOKEN:
                $this->x_axis = $token_manager->getNextToken();
                break;
            case self::Y_AXIS_TOKEN:
                $this->y_axis = $token_manager->getNextToken();
                break;
            case self::TITLE_TOKEN:
                $this->title = $token_manager->getNextToken();
                break;
            case self::X_ORDER_TOKEN:
                $this->x_order = $token_manager->getNextToken();
                break;
            case self::Y_ORDER_TOKEN:
                $this->y_order = $token_manager->getNextToken();
                break;
            case self::ONLY_USE_TOKEN:
                $this->separateFilter($token_manager);
                break;
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }

    public function getData() {
        return $this->data;
    }

    protected function sort() {

    }
}