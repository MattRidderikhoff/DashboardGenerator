<?php

namespace App\Services;

use App\Entities\Chart;
use App\Entities\ChartGroup;
use App\Entities\Node;

class Tokenizer
{
    const LITERALS = [Node::NODE_START_TOKEN, Node::TITLE_TOKEN, Chart::X_AXIS_TOKEN, Chart::Y_AXIS_TOKEN,
        Chart::X_ORDER_TOKEN, Chart::Y_ORDER_TOKEN, Chart::LINE_TOKEN, ChartGroup::ORIENT_TOKEN, ChartGroup::ADD_CHART_TOKEN,
        Node::NODE_END_TOKEN];

    public function generateTokens($input_lines) {

        $token_string = str_replace("\n", " ", $input_lines);
        foreach (Tokenizer::LITERALS as $literal) {
            $token_string = str_replace($literal, '_'.$literal.'_', $token_string);
        }
        $tokens = explode('_', $token_string);

        $cleaned_tokens = [];
        foreach ($tokens as $token) {
            $cleaned_token = trim(str_replace('"', "", $token));
            if (!empty($cleaned_token)) {
                $cleaned_tokens[] = $cleaned_token;
            }
        }

        return $cleaned_tokens;
    }
}