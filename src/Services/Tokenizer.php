<?php

namespace App\Services;

class Tokenizer
{
    const LITERALS = ['Create', 'Title is', 'X is', 'Y is', 'Order', 'Each line is', 'Orient', 'Add', 'End'];

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