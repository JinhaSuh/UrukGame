<?php

namespace controller;

use exception\UserException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use service\InventoryService;
use dto\User;

use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../service/InventoryService.php';

class InventoryController
{
    private InventoryService $inventoryService;

    public function __construct()
    {
        $this->inventoryService = new InventoryService();
    }

    public function selectInventory(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $inventory = $this->inventoryService->select_inventory($input);
            $response->getBody()->write(json_encode($inventory));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write($e->getCode() . $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(501);
        }
    }

    public function upgradeEquipment(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $inventory = $this->inventoryService->upgrade_equipment($input);
            $response->getBody()->write(json_encode($inventory));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write($e->getCode() . $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(501);
        } catch (UserException $e) {
        }
    }
}
