<?php

namespace App\Github;

use App\Security\User;
use Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubUserProvider
{
    private $githubClient;
    private $githubId;
    private $httpClient;

    /**
     * GithubUserProvider constructor.
     * @param $githubClient
     * @param $githubId
     * @param $httpClient
     */
    public function __construct($githubClient, $githubId, HttpClientInterface $httpClient)
    {
        $this->githubClient = $githubClient;
        $this->githubId = $githubId;
        $this->httpClient = $httpClient;
    }

    public function loadUserFromGithub(string $code)
    {
        $url = sprintf("https://github.com/login/oauth/access_token?client_id=%s&client_secret=%s&code=%s",
        $this->githubClient, $this->githubId, $code);

        $response = $this->httpClient->request(
            "POST", $url, [
                "headers" => [
                    "Accept" => "application/json"
                ]
            ]
        );

        $token = $response->toArray()["access_token"];

        $response = $this->httpClient->request("GET", "https://api.github.com/user", [
            "headers" => [
                "Authorization" => "token ". $token
            ]
        ]);

        $data = $response->toArray();

        return new User($data);
    }


}