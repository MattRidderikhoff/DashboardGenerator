<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-10-03
 * Time: 11:17 AM
 */

namespace App\Entities;


class PieChart extends Chart
{
    private $x_axis;
    private $y_axis;

    public function __construct()
    {
        $this->type = Node::TYPE_PIE_CHART;
    }

    // Note: currently this is a duplicate of BarChart->evaluate()
    public function evaluate($dataset)
    {
        $data = [];
        foreach ($dataset as $row) {

            $x_value = trim($row[$this->x_axis]);
            if (!isset($data[$x_value])) {
                $data[$x_value]['x_value'] = $x_value;
                $data[$x_value]['y_value'] = 0;
            }

            $data[$x_value]['y_value'] += intval($row[$this->y_axis]);
        }

        $this->data['colours'] = [];
        foreach ($data as $column) {
            $x_value = $column['x_value'];
            $this->data['x_values'][$x_value] = $x_value;
            $this->data['y_values'][$x_value] = $column['y_value'];
            $this->data['colours'][$x_value] = $this->getNewColour($this->data['colours']);
        }

        if (isset($this->order)) {
            $this->sortByX();
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
                $this->order = $token_manager->getNextToken();
                break;
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }
}