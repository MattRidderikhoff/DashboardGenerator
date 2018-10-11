<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:15 PM
 */

namespace App\Entities;

abstract class Node
{
    const TYPE_DATASET = 'datasets';
    const TYPE_CHART_GROUP = 'group';
    const TYPE_BAR_CHART = 'bar_chart';
    const TYPE_LINE_CHART = 'line_chart';
    const TYPE_PIE_CHART = 'pie_chart';

    const NODE_START_TOKEN = 'Create';
    const NODE_END_TOKEN = 'End';
    const TITLE_TOKEN = 'Title is';
    const ADD_ITEM_TOKEN = 'Add';

    protected $title;
    protected $type;
    protected $y_axis;
    protected $x_axis;

    public function parse($token_manager) {
        while (!$token_manager->checkNextToken('End')) {
            $this->addAttribute($token_manager, $token_manager->getNextToken());
        }

        // discard 'End' token
        $token_manager->getNextToken();
    }

    abstract public function addAttribute(TokenManager $token_manager, $token);

    abstract public function evaluate($dataset);

    public function getTitle() {
        return $this->title;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getYAxis()
    {
        return $this->y_axis;
    }

    /**
     * @return mixed
     */
    public function getXAxis()
    {
        return $this->x_axis;
    }


}