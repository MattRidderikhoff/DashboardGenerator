<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-28
 * Time: 1:27 PM
 */

namespace App\Entities;

use Symfony\Component\Serializer\SerializerInterface;

class TokenManager
{
    private $tokens;
    private $serializer;

    public function __construct($tokens, SerializerInterface $serializer)
    {
        $this->tokens = $tokens;
        $this->serializer = $serializer;
    }

    public function generateNodes() {
        $nodes = [];
        while ($this->hasNextToken()) {

            $node = null;
            if ($this->getAndCheckNextToken(Node::NODE_START_TOKEN)) {

                $next_token = $this->getNextToken();
                switch ($next_token) {
                    case 'Datasets':
                        $node = new Datasets($this->serializer);
                        break;
                    case 'Bar':
                        $node = new BarChart();
                        break;
                    case 'Line':
                        $node = new LineChart();
                        break;
                    case 'Pie':
                        $node = new PieChart();
                        break;
                    case 'Group':
                        $node = new ChartGroup();
                        break;
                }

                $nodes[] = $node;
                $node->parse($this);

            } else {
                throw new \Exception("Incorrectly formatted DSL (didn't start chart/group with 'Create')");
            }
        }

        return $nodes;
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