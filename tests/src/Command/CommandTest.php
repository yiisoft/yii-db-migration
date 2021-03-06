<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Db\Migration\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Yiisoft\Yii\Db\Migration\Tests\BaseTest;

abstract class CommandTest extends BaseTest
{
    protected function getApplication(): Application
    {
        $container = $this->getContainer();
        $application = $container->get(Application::class);

        $loader = new ContainerCommandLoader(
            $container,
            $this->getParams()['yiisoft/yii-console']['commands']
        );
        $application->setCommandLoader($loader);

        return $application;
    }

    protected function findMigrationClassNameInOutput(string $output): string
    {
        $words = explode(' ', $output);

        foreach ($words as $word) {
            if (preg_match('/^\s*M\d{12}\D.*/', $word)) {
                return trim($word);
            }
        }

        return '';
    }
}
