<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:27 PM
 */

namespace App\Entities;


class TokenManager
{
    private $tokens;

    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    public function getNextToken() {
        return array_shift($this->tokens);
    }

    public function checkNextToken($regex) {
        return (strpos($regex, reset($this->tokens)) !== false);
    }

    public function getAndCheckNextToken($regex) {
        if (!$this->checkNextToken($regex)) {
            throw new \Exception('Unexpected token');
        } else {
            return $this->getNextToken();
        }
    }

    public function hasNextToken() {
        return !empty($this->tokens);
    }
}