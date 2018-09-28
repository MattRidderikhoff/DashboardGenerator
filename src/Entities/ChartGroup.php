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

    private $title;
    private $chart_titles = [];
    private $orientation;

    public function evaluate()
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
}