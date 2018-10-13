<?php
/**
 * Created by PhpStorm.
 * User: matthewridderikhoff
 * Date: 2018-10-12
 * Time: 4:20 PM
 */

namespace App\Services;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class RequestHandler
{
    private $input_path;
    
    public function __construct()
    {
        $this->input_path = str_replace('Services', '', __DIR__ ) . "Input/";
    }

    public function handleHomeRequest(Request $request) {
        $remove_datasets = $request->query->get('remove_datasets');
        if (isset($remove_datasets)) {
            $this->removeDatasets($remove_datasets);
        }

        $add_dataset = $request->files->get('add_dataset');
        if (isset($add_dataset)) {
            $this->addDataset($add_dataset);
        }

        $new_program = $request->files->get('new_program');
        if (isset($new_program)) {
            $this->changeProgram($new_program);
        }
    }

    private function removeDatasets($datasets) {
        $filesystem = new Filesystem();

        foreach ($datasets as $dataset) {
            $dataset_path = $this->input_path . $dataset;
            $filesystem->remove($dataset_path);
        }
    }

    private function addDataset(UploadedFile $dataset) {
        $dataset->move($this->input_path, $dataset->getClientOriginalName());
    }

    private function changeProgram(UploadedFile $new_dsl) {
        $filesystem = new Filesystem();

        $filesystem->remove($this->input_path . 'input.txt');
        $new_dsl->move($this->input_path, 'input.txt');
    }

}