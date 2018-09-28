<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-09-27
 * Time: 9:07 PM
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function renderHomepage() {

        // 1. Parse dataset.csv into a structure we can use
        $dataset_path = str_replace('Controller', '', __DIR__ ). 'Input/movies_2011.csv';
        $dataset = $this->get('serializer')->decode(file_get_contents($dataset_path), 'csv');

        // 2. Tokenize Input.txt and generate an AST
        // 3. Evaluate AST and generate the javascript + html for each individual chart
        // 4. Group chart html if applicable
        // 5. Return HTML and javascript back to index.php

        return $this->render('base.html.twig');
    }
}