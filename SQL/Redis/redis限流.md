计数器
```php
function msetime()
{
  list($msec, $sec) = explode(' ', microtime());

  return (float) sprintf('%.0f', (floatval($msec) + floatval($sec) * 1000));
}

function isActionAllowed($userId, $action, $period, $maxCount)
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $key = sprintf('hist:%s:%s', $userId, $action);
    $now = msetime();

    $pipe = $redis->multi(Redis::PIPELINE);
    $pipe->zadd($key, $now, $now);
    $pipe->zremrangebyscore($key, 0, $now - $period * 1000);
    $pipe->zcard($key);
    $pipe->expire($key, $period + 1);
    $replies = $pipe->exec();

    return $replies[2] <= $maxCount;
}

for ($i=0; $i < 10; $i++) {
  var_dump(isActionAllowed("110", "reply", 60, 5));
  sleep(1);
}
```