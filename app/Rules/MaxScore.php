<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxScore implements Rule
{
    private $maxScore;

    public function __construct($maxScore)
    {
        $this->maxScore = $maxScore;
    }

    public function passes($attribute, $value)
    {
        return $value <= $this->maxScore;
    }

    public function message()
    {
        return "分数不能超过 {$this->maxScore} 分";
    }
}