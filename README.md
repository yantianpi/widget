# widget
daily widget

## ÎÄ¼þËø(FileLock)
```php
require 'vendor/autoload.php';

use Yantp\Widget\FileLock;

$filePath = __FILE__;
$times = 10;
echo 'start' . PHP_EOL;
$fileLockObj = new FileLock($filePath);
if ($fileLockObj->lock()) {
    echo "lock success" . PHP_EOL;
    for ($i = 1; $i <= $times; $i++) {
        echo "sleep {$i}" . PHP_EOL;
        sleep(1);
    }
    $fileLockObj->unlock();
} else {
    echo "lock fail" . PHP_EOL;
}

echo 'end' . PHP_EOL;
```
