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

    public function __construct()
    {
        $this->type = Node::TYPE_BAR_CHART;
    }

    // TODO: add in type-checking regex service?
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
            case self::DATASET_TOKEN:
                $this->dataset_id = $token_manager->getNextToken();
                break;
            case self::X_AXIS_TOKEN:
                $this->x_axis = $token_manager->getNextToken();
                $this->x_label = $this->x_axis;
                if ($token_manager->checkNextToken(self::ALIAS_TOKEN)) {
                    $token_manager->getNextToken();
                    $this->x_label = $token_manager->getNextToken();
                }
                break;
            case self::Y_AXIS_TOKEN:
                $this->y_axis = $token_manager->getNextToken();
                $this->y_label = $this->y_axis;
                if ($token_manager->checkNextToken(self::ALIAS_TOKEN)) {
                    $token_manager->getNextToken();
                    $this->y_label = $token_manager->getNextToken();
                }
                break;
            case self::TITLE_TOKEN:
                $this->title = $token_manager->getNextToken();
                break;
            case self::X_ORDER_TOKEN:
                $this->order = $token_manager->getNextToken();
                break;
            case self::ONLY_USE_TOKEN:
                $this->separateFilter($token_manager);
                break;
            case self::SCALE_KEY:
                $this->scale_by = $token_manager->getNextToken();
                break;
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }
}