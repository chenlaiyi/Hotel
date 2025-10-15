<?php

namespace common\rpc\services;

class BaseRpcService
{
    public function writeJson(int $statusCode = 200, string $msg = '获取成功', array $result = []): array
    {
        return [
            'code' => $statusCode,
            'message' => $msg,
            'data' => $result
        ];
    }

}