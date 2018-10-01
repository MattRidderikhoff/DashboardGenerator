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

        /** 3. Evaluate AST and generate the javascript + html for each individual chart **/
        // todo: ensure title uniqueness

        /** 4. Arrange Charts in ChartGroups, if applicable **/
        /** 5. Return HTML and javascript back to index.php **/

        $nodes_json = [];
        foreach ($nodes as $node) {
            $nodes_json[] = json_encode($node);
        }
        return $this->render('base.html.twig',
            ['nodes' => $nodes]);
    }
}