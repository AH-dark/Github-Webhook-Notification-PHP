<?php
namespace GitHub;

interface GitHubEvent {
    function __construct($data);
    public function getMessage(): string;
}