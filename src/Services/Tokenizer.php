<?php

namespace App\Services;

use App\Entities\Chart;
use App\Entities\ChartGroup;
use App\Entities\Node;
use App\Entities\PieChart;

class Tokenizer
{
    const LITERALS = [Node::NODE_START_TOKEN, Node::TITLE_TOKEN, Chart::X_AXIS_TOKEN, Chart::Y_AXIS_TOKEN,
        Chart::X_ORDER_TOKEN, Chart::Y_ORDER_TOKEN, 'Each line is', ChartGroup::ORIENT_TOKEN, ChartGroup::ADD_CHART_TOKEN,
        Node::NODE_END_TOKEN, PieChart::VALUE_TOKEN, PieChart::CATEGORY_TOKEN, PieChart::CATEGORY_ORDER_TOKEN,
        Chart::ONLY_USE_TOKEN, Chart::GREATER_THAN_KEY, Chart::LESS_THAN_KEY, Chart::GREATER_THAN_OR_EQUAL_KEY,
        Chart::LESS_THAN_OR_EQUAL_KEY, Chart::EXCLUDE_KEY, Chart::INCLUDE_KEY];

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