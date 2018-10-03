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

    public function evaluate($dataset)
    {
        // TODO: Implement evaluate() method.
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