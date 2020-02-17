<?php

namespace Mikecheng\Pay\Gateways\Alipay;

use Mikecheng\Pay\Contracts\GatewayInterface;
use Mikecheng\Pay\Events;
use Mikecheng\Pay\Exceptions\GatewayException;
use Mikecheng\Pay\Exceptions\InvalidConfigException;
use Mikecheng\Pay\Exceptions\InvalidSignException;
use Mikecheng\Supports\Collection;

class ScanGateway implements GatewayInterface
{
    /**
     * Pay an order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     *
     * @return Collection
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['method'] = 'alipay.trade.precreate';
        $payload['biz_content'] = json_encode(array_merge(
            json_decode($payload['biz_content'], true),
            ['product_code' => '']
        ));
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Alipay', 'Scan', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
