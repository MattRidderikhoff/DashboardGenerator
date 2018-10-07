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
    const CATEGORY_TOKEN = "Category is";
    const VALUE_TOKEN = "Value is";
    const CATEGORY_ORDER_TOKEN = "Order Category";

    private $x_axis; // Category
    private $y_axis; // Value

    public function __construct()
    {
        $this->type = Node::TYPE_PIE_CHART;
    }

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
            case self::CATEGORY_TOKEN:
                $this->x_axis = $token_manager->getNextToken();
                break;
            case self::VALUE_TOKEN:
                $this->y_axis = $token_manager->getNextToken();
                break;
            case self::TITLE_TOKEN:
                $this->title = $token_manager->getNextToken();
                break;
            case self::CATEGORY_ORDER_TOKEN:
                $this->order = $token_manager->getNextToken();
                break;
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }
}