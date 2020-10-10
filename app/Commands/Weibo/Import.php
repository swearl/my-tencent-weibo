<?php

namespace App\Commands\Weibo;

use App\Libraries\TencentWeibo;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Exception;

class Import extends BaseCommand {
    protected $group = 'Weibo';

    protected $name = 'weibo:import';

    public function run(array $params) {
        try {
            if (empty($params)) {
                throw new Exception('参数有误');
            }
            $qq = trim($params[0]);
            $file = WRITEPATH . 'cache/' . $qq . '.html';
            if (!file_exists($file)) {
                throw new Exception('备份文件不存在');
            }
            CLI::write('找到备份文件, 读取中...');
            $dom = TencentWeibo::loadBackupFile($file);
            CLI::write('读取完毕, 开始查找...');
            $items = TencentWeibo::getItems($dom);
            $total = $items->length;
            $i = 0;
            CLI::write('开始保存...');
            foreach ($items as $item) {
                $node = TencentWeibo::parseItem($item->childNodes);
                TencentWeibo::saveItem($node);
                CLI::showProgress(++$i, $total);
            }
            CLI::showProgress(false);
            CLI::write('保存完毕');
        } catch (Exception $e) {
            $this->showError($e);
        }
    }
}
