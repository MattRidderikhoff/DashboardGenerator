<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-10-09
 * Time: 12:15 PM
 */

namespace App\Entities;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class Datasets extends Node
{
    private $datasets = [];
    private $input_path;

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->input_path = str_replace('Entities', '', __DIR__ ) . "Input/";
        $this->type = Node::TYPE_DATASET;
        $this->serializer = $serializer;
    }

    public function addAttribute(TokenManager $token_manager, $token)
    {
        switch ($token) {
            case self::ADD_ITEM_TOKEN:
                $this->datasets[] = $token_manager->getNextToken();
                break;
            default:
                // discard value for unsupported attribute
                $token_manager->getNextToken();
                break;
        }
    }

    public function evaluate($dataset)
    {
        $available_datasets = [];
        foreach ($this->datasets as $dataset_name) {
            $dataset_path = $this->input_path . $dataset_name . '.csv';

            $available_datasets[$dataset_name] = $this->serializer->decode(file_get_contents($dataset_path), 'csv');
        }
        return $available_datasets;
    }
}