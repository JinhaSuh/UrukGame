<?php

namespace controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use service\InventoryService;

use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../dto/User.php';
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
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function upgradeEquipment(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $equipment = $this->inventoryService->upgrade_equipment($input);
            $response->getBody()->write(json_encode($equipment));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function equipEquipment(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $inventory = $this->inventoryService->equip_equipment($input);
            $response->getBody()->write(json_encode($inventory));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }

    public function selectEquipSlot(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $equipSlot = $this->inventoryService->select_equipSlot($input);
            $response->getBody()->write(json_encode($equipSlot));
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write($e->getCode().": ". $e->getMessage());
            return $response->withHeader('content-type', 'application/json')->withStatus(200);
        }
    }
}
