<?php

namespace App\Services;

class Tokenizer
{
    const LITERALS = ['Create', 'Title is', 'X is', 'Y is', 'End'];

    public function generateTokens($input_lines) {

        $tokens = [];
        foreach ($input_lines as $input_line) {
            // Todo: split each line on the LITERALS, add both sides to $tokens
        }

        return $tokens;
    }

    public function generateAST($tokens) {
        // Todo: complete
        return $tokens;
    }
}