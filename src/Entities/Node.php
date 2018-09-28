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
    const NODE_START_TOKEN = 'Create';
    const NODE_END_TOKEN = 'End';
    const TITLE_TOKEN = 'Title is';

    public function parse($token_manager) {
        while (!$token_manager->checkNextToken('End')) {
            $this->addAttribute($token_manager, $token_manager->getNextToken());
        }

        // discard 'End' token
        $token_manager->getNextToken();
    }

    abstract public function addAttribute(TokenManager $token_manager, $token);

    abstract public function evaluate();
}