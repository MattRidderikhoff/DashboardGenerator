<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:56 PM
 */

namespace App\Entities;


class ChartGroup extends Node
{
    const ORIENT_TOKEN = 'Orient';
    const ADD_CHART_TOKEN = 'Add';

    private $chart_titles = [];
    private $orientation;

    public function __construct()
    {
        $this->type = Node::TYPE_CHART_GROUP;
    }

    public function evaluate($dataset)
    {
        // TODO: Implement evaluate() method.
    }

    public function addAttribute(TokenManager $token_manager, $token)
    {
        switch ($token) {
            case self::ADD_CHART_TOKEN:
                $this->chart_titles[] = $token_manager->getNextToken();
                break;
            case self::TITLE_TOKEN:
                $this->title = $token_manager->getNextToken();
                break;
            case self::ORIENT_TOKEN:
                $this->orientation = $token_manager->getNextToken();
                break;
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }

    public function getChartTitles() {
        return $this->chart_titles;
    }

    public function getOrientation() {
        return $this->orientation;
    }
}