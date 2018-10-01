<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-27
 * Time: 9:07 PM
 */

namespace App\Controller;

use App\Entities\BarChart;
use App\Entities\ChartGroup;
use App\Entities\LineChart;
use App\Entities\Node;
use App\Entities\TokenManager;
use App\Services\Tokenizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController extends AbstractController
{
    /**
     * @param SerializerInterface $serializer
     * @param Tokenizer $tokenizer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function renderHomepage(SerializerInterface $serializer, Tokenizer $tokenizer) {
        $input_path = str_replace('Controller', '', __DIR__ ) . "Input/";

        /** 1. Parse dataset into a structure we can use **/
        $dataset_path = $input_path . 'movies_2011.csv';
        $dataset = $serializer->decode(file_get_contents($dataset_path), 'csv');

        /** 2. Tokenize Input.txt and generate an AST **/
        // 2a. generate tokens
        $dsl_input_path = $input_path . 'input.txt';
        $dsl_input_string = file_get_contents($dsl_input_path);

        $tokens = $tokenizer->generateTokens($dsl_input_string);
        $token_manager = new TokenManager($tokens);

        // 2b. generate nodes of the AST
        $nodes = $this->generateNodes($token_manager);

        /** 3. Evaluate each node with the provided dataset **/
        // todo: ensure title uniqueness

        /** 4. Arrange Charts in ChartGroups, if applicable **/
        // todo: this is done in base.html.twig, and may require making a .js file

        /** 5. Render the page with the evaluated nodes **/
        $charts_and_groups = $this->separateChartsAndGroups($nodes);

        return $this->render('base.html.twig',
            ['charts' => $charts_and_groups['charts'],
             'groups' => $charts_and_groups['groups']]);
    }

    private function generateNodes(TokenManager $token_manager) {
        $nodes = [];
        while ($token_manager->hasNextToken()) {

            if ($token_manager->getAndCheckNextToken(Node::NODE_START_TOKEN)) {

                $node = null;
                $next_token = $token_manager->getNextToken();
                switch ($next_token) {
                    case 'Bar':
                        $node = new BarChart();
                        break;
                    case 'Line':
                        $node = new LineChart();
                        break;
                    case 'Group':
                        $node = new ChartGroup();
                        break;
                }

                $nodes[] = $node;
                $node->parse($token_manager);
            } else {
                throw new \Exception("Incorrectly formatted DSL (didn't start chart/group with 'Create')");
            }
        }

        return $nodes;
    }

    private function separateChartsAndGroups($nodes) {
        $charts_and_groups = [];
        foreach ($nodes as $node) {
            if ($node->getType() == Node::TYPE_CHART_GROUP) {
                $charts_and_groups['groups'][] = $node;
            } else {
                $charts_and_groups['charts'][] = $node;
            }
        }

        return $charts_and_groups;
    }
}