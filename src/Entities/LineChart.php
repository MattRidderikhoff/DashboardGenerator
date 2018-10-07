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
    private $line;
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
                $line_value = trim($row[$this->line]);
                $x_value = trim($row[$this->x_axis]);

                if (!isset($data[$line_value])) {
                    $data[$line_value]['line_value'] = $line_value;
                }

                if (!isset($data[$line_value][$x_value])) {
                    $data[$line_value]['x_values'][$x_value]['x_value'] = $x_value;
                    $data[$line_value]['x_values'][$x_value]['y_value'] = 0;
                }

                $data[$line_value]['x_values'][$x_value]['y_value'] += doubleval($row[$this->y_axis]);
            }
        }

        $this->data['colours'] = [];
        foreach ($data as $d) {
            $line_value = $d['line_value'];
            foreach ($data[$line_value]['x_values'] as $l ) {
                $x_value = $l['x_value'];
                $this->data['x_values'][$x_value] = $x_value;

                $this->data['lines'][$line_value] = $line_value;
                $this->data['y_values'][$line_value][$x_value] = $l['y_value'];
            }
            $this->data['colours'][$line_value] = $this->getNewColour($this->data['colours']);
        }

        // COMMENT: order by y doesn't make much sense for line graphs
        if (isset($this->x_order)) {
            $this->order = $this->x_order;
            $this->sortLinesByX();
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
            case self::LINE_TOKEN:
                $this->line = $token_manager->getNextToken();
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

    protected function sortLinesByX(){
        if ($this->order === Chart::DESCENDING_KEY) {
            krsort($this->data['x_values']);
            foreach ($this->data['lines'] as $line) {
                krsort($this->data['y_values'][$line]);
            }
            if(isset($this->data['colours'])) {
                krsort($this->data['colours']);
            }
        } else {
            ksort($this->data['x_values']);
            foreach ($this->data['y_values'] as $line) {
                ksort($line);
            }
            if(isset($this->data['colours'])) {
                ksort($this->data['colours']);
            }
        }
    }
}