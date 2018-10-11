<?php

namespace App\Services;

use App\Entities\Chart;
use App\Entities\ChartGroup;
use App\Entities\Datasets;
use App\Entities\LineChart;
use App\Entities\Node;
use App\Entities\PieChart;

class Tokenizer
{
    // todo: move LINE_TOKEN into LineChart

    // literals that can begin a line
    const STATEMENT_START_LITERALS = [Node::NODE_START_TOKEN, Node::TITLE_TOKEN, Chart::DATASET_TOKEN, Node::NODE_END_TOKEN,
        Chart::X_AXIS_TOKEN, Chart::Y_AXIS_TOKEN, Chart::Y_ORDER_TOKEN, Chart::X_ORDER_TOKEN, PieChart::CATEGORY_TOKEN, PieChart::VALUE_TOKEN,
        PieChart::CATEGORY_ORDER_TOKEN, LineChart::LINE_TOKEN, ChartGroup::ORIENT_TOKEN, Node::ADD_ITEM_TOKEN, Chart::ONLY_USE_TOKEN];

    // literals that are used for logic, but cannot be a sub-literal
    const LOGIC_LITERALS = [Chart::GREATER_THAN_OR_EQUAL_KEY, Chart::LESS_THAN_OR_EQUAL_KEY, Chart::EXCLUDE_KEY, Chart::INCLUDE_KEY];

    // literals that can be combined to make
    const BASE_LOGIC_LITERALS = [Chart::GREATER_THAN_KEY,  Chart::LESS_THAN_KEY, Chart::EQUAL_KEY];

    public function generateTokens($input_lines) {
        $input_lines = explode("\n", $input_lines);

        $tokens = [];
        foreach ($input_lines as $input_line) {

            if (empty($input_line)) continue;

            foreach (self::STATEMENT_START_LITERALS as $literal) {

                if (strpos($input_line, $literal) !== false) {
                    $input_line = str_replace($literal, '', $input_line);

                    $sub_tokens = $this->generateSubStatementTokens(trim(str_replace('"', "", $input_line)), $literal);
                    $tokens = array_merge($tokens, [$literal], $sub_tokens);

                    continue;
                }
            }

        }
        return $tokens;
    }

    private function generateSubStatementTokens($remaining_input_line, $literal) {
        if (($literal == Chart::X_AXIS_TOKEN || $literal == Chart::Y_AXIS_TOKEN) && strpos($remaining_input_line, Chart::ALIAS_TOKEN) !== false) {
            $sub_tokens = $this->separateLiteral(Chart::ALIAS_TOKEN, $remaining_input_line);
            $return_tokens = [];
            foreach ($sub_tokens as $sub_token) {
                $return_tokens[] = trim($sub_token);
            }

            return $return_tokens;
        } elseif ($literal == Chart::ONLY_USE_TOKEN) {

            $sub_tokens = [];
            foreach (self::LOGIC_LITERALS as $sub_literal) {
                if (strpos($remaining_input_line, $sub_literal) !== false) {
                   $sub_tokens = $this->separateLiteral($sub_literal, $remaining_input_line);
                   continue;
                }
            }

            if (empty($sub_tokens)) {
                foreach (self::BASE_LOGIC_LITERALS as $sub_literal) {
                    if (strpos($remaining_input_line, $sub_literal) !== false) {
                        $sub_tokens = $this->separateLiteral($sub_literal, $remaining_input_line);
                        continue;
                    }
                }
            }

            $return_tokens = [];
            foreach ($sub_tokens as $sub_token) {
                $return_tokens[] = trim($sub_token);
            }

            return $return_tokens;
        } elseif ($literal == Node::NODE_END_TOKEN) {
            return [];
        } else {
            return [$remaining_input_line];
        }
    }

    private function separateLiteral($literal, $input_line) {
         $token_string = str_replace($literal, '_'.$literal.'_', $input_line);
         return explode('_', $token_string);
    }
}