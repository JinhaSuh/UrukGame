<?php

namespace controller;

use dto\User;
use service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../service/UserService.php';

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function selectAllUser(Request $request, Response $response)
    {
        $users = $this->userService->select_users();

        $response->getBody()->write(json_encode($users));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function selectUser(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();
        $user_id = $requestBody["userId"];

        $user = new User();
        $user->set_user_id($user_id);

        $result = $this->userService->select_user($user);
        //결과를 User 객체로
        $user = User::Deserialize($result);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
