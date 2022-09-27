<?php

namespace App\Dispatcher;

use App\Controller\UserController;
use App\Mapper\UserMapper;

class Dispatcher
{
    private UserController $userController;

    public function __construct()
    {
        $this->userController = new UserController();
    }

    private function parseRequest(string $request): array
    {
        $result = [];

        $splitRequest = preg_split('/\r\n/', $request);

        //var_dump($splitRequest);

        $head = $splitRequest[0];
        $chunks = preg_split('/\s/', $head);
        $result['method'] = $chunks[0];
        $result['path'] = preg_split('/\//', $chunks[1]);

        $result['body'] = array_pop($splitRequest);

        if (json_decode($result['body'])) {
            $result['body'] = json_decode($result['body'], true);
        } else {
            $result['body'] = null;
        }

        return $result;
    }

    public function dispatch(string $request): string
    {
        $info = $this->parseRequest($request);
        $responseBody = '';

        switch ($info['method']) {
            case 'GET': {
                if ($info['body'] != null) {
                    $responseBody = $this->userController->getOne($info['body']['email']);
                } else {
                    $responseBody = $this->userController->getAll();
                }
                break;
            }
            case 'POST': {
                $responseBody = $this->userController->add(UserMapper::map($info['body']));
                break;
            }
            case 'PUT': {
                $responseBody = $this->userController->update(UserMapper::map($info['body']));
                break;
            }
            case 'DELETE': {
                $this->userController->delete($info['body']);
                break;
            }
            default: {
                die();
            }
        }

        return $this->constructHttpResponse($responseBody);
    }

    private function constructHttpResponse(string $body = null): string
    { //TODO figure out correct http response
        $response = "HTTP/1.1 200 OK\r\nConnection: Closed\r\nContent-Type: application/json\r\n\r\n$body";
        //echo $response;
        return $response;
    }
}
