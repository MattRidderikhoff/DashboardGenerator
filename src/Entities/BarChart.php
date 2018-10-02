<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:20 PM
 */

namespace App\Entities;


class BarChart extends Chart
{
    private $x_axis;
    private $y_axis;
    private $x_order;
    private $y_order;

    public function __construct()
    {
        $this->type = Node::TYPE_BAR_CHART;
    }

    // TODO: add in type-checking regex service?
    public function evaluate($dataset)
    {
        $data = [];
        foreach ($dataset as $row) {

            $x_value = trim($row[$this->x_axis]);
            if (!isset($data[$x_value])) {
                $data[$x_value]['x_values'] = $x_value;
                $data[$x_value]['y_values'] = 0;
            }

            $data[$x_value]['y_values'] += intval($row[$this->y_axis]);
        }

        $this->data['colours'] = [];
        foreach ($data as $column) {
            $this->data['x_values'][] = $column['x_values'];
            $this->data['y_values'][] = $column['y_values'];
            $this->data['colours'][] = $this->getNewColour($this->data['colours']);
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
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }

    public function getData() {
        return $this->data;
    }
}