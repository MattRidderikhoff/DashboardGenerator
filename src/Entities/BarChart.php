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

    public function evaluate($dataset)
    {
        $data = [];
        foreach ($dataset as $row) {

            // TODO: add in type-checking regex service
            // ASSUME: x are distinct

            $x_value = trim($row[$this->x_axis]);
            if (!isset($data[$x_value])) {
                $data[$x_value]['x_value'] = $x_value;
                $data[$x_value]['y_value'] = 0;
                $data[$x_value]['size'] = 0;
            }

            ++$data[$x_value]['size'];
            $data[$x_value]['y_value'] += intval($row[$this->y_axis]);

            if ($data[$x_value]['x_value'] === "?")
            {
                unset($data[$x_value]);
            }
        }

        foreach ($data as $column) {
            $this->data['x_value'][] = $column['x_value'];

            // divide y_value by size?
            $this->data['y_value'][] = $column['y_value'];
        }

        // temp
        return $this->data;
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