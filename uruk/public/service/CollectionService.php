<?php

namespace service;

use exception\InvalidRequestBody;
use repository\CollectionRepository;

require_once __DIR__ . '/../repository/CollectionRepository.php';
require_once __DIR__ . '/../exception/InvalidRequestBody.php';

class CollectionService
{
    private CollectionRepository $collectionRepository;

    public function __construct()
    {
        $this->collectionRepository = new CollectionRepository();
    }

    /**
     * @throws InvalidRequestBody
     */
    public function select_collection($user)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->collectionRepository->select_collection($user["user_id"]);
    }
}
