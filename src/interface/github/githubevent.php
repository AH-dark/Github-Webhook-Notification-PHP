<?php

namespace GitHub;

use Exception;

interface GitHubEvent
{
    function __construct($data);

    /**
     * @throws Exception
     * @return string
     */
    public function getMessage(): string;
}