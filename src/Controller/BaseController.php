<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-27
 * Time: 9:07 PM
 */

namespace App\Controller;

use App\Entities\ChartGroup;
use App\Entities\Node;
use App\Entities\TokenManager;
use App\Services\RequestHandler;
use App\Services\Tokenizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController extends AbstractController
{
    private $datasets = [];

    /**
     * @param Request $request
     * @param RequestHandler $request_handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderHome(Request $request, RequestHandler $request_handler) {
        $request_handler->handleHomeRequest($request);

        $finder = new Finder();
        $finder->files()->in($this->getInputPath())->name('*.csv');

        $filenames = [];
        foreach ($finder as $file) {
            $filenames[] = $file->getRelativePathname();
        }
        sort($filenames);

        $program = explode("\n", $this->getProgramString());

        return $this->render('home.html.twig',
            ['datasets' => $filenames,
             'program' => $program]);
    }

    /**
     * @param SerializerInterface $serializer
     * @param Tokenizer $tokenizer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function renderCharts(SerializerInterface $serializer, Tokenizer $tokenizer) {

        $tokens = $tokenizer->generateTokens($this->getProgramString());
        $token_manager = new TokenManager($tokens, $serializer);

        $nodes = $token_manager->generateNodes();

        $datasets_entity = array_shift($nodes);
        $this->datasets = $datasets_entity->evaluate(null);

        foreach ($nodes as $node) {
            if ($node instanceof ChartGroup) {
                $node->evaluate(null);
            } else {
                $dataset = $this->datasets[$node->getDatasetId()];
                $node->evaluate($dataset);
            }
        }

        $charts_and_groups = $this->separateChartsAndGroups($nodes);

        return $this->render('base.html.twig',
            ['charts' => $charts_and_groups['charts'],
             'groups' => $charts_and_groups['groups']]);
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

    private function getInputPath() {
        return str_replace('Controller', '', __DIR__ ) . "Input/";
    }

    private function getProgramString() {
        $dsl_input_path = $this->getInputPath() . 'input.txt';
        return file_get_contents($dsl_input_path);
    }
}