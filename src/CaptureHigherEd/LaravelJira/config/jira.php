<?php

return [

    /**
     * Atlassian API Token for authenticating with the Jira REST API.
     *
     * This is auto-built from JIRA_API_EMAIL and JIRA_API_TOKEN as a base64-encoded
     * "email:token" string (HTTP Basic Auth format required by Atlassian Cloud).
     *
     * To generate a token: https://id.atlassian.com/manage-profile/security/api-tokens
     */
    'token' => env("JIRA_API_EMAIL") && env("JIRA_API_TOKEN")
        ? base64_encode(trim(env("JIRA_API_EMAIL") . ':' . env("JIRA_API_TOKEN")))
        : null,

    /**
     * Jira domain: ex. https://[client name].atlassian.net
     */
    'domain' => env("JIRA_API_DOMAIN")
];
