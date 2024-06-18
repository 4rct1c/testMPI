<?php

namespace App\Helpers;

use App\Models\TestStatus;

class TestResult
{

    public function __construct(
        public TestStatus $status,
        public string $message
    )
    {

    }
}
