<?php

return [

    /**
     * Personal access token to use for jira service
     *
     * This should be a base64 encoded string in the format "test@[domain]:jiraPersonalAccessToken"
     *
     * @link https://confluence.atlassian.com/enterprise/using-personal-access-tokens-1026032365.html
     */
    'token' => env("JIRA_API_EMAIL") && env("JIRA_API_TOKEN")
        ? base64_encode(trim(env("JIRA_API_EMAIL") . ':' . env("JIRA_API_TOKEN")))
        : null,

    /**
     * Jira domain: ex. https://[client name].atlassian.net
     */
    'domain' => env("JIRA_API_DOMAIN")
];
