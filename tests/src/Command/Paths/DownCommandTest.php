<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Db\Migration\Tests\Command\Paths;

use Symfony\Component\Console\Tester\CommandTester;
use Yiisoft\Yii\Console\ExitCode;

final class DownCommandTest extends PathsCommandTest
{
    public function testExecute(): void
    {
        $this->createMigration('Create_Post', 'table', 'post', ['name:string']);
        $this->createMigration('Create_User', 'table', 'user', ['name:string']);
        $this->applyNewMigrations();

        $this->assertExistsTables('post', 'user');

        $command = $this->getCommand();

        $command->setInputs(['yes']);

        $this->assertEquals(ExitCode::OK, $command->execute([]));

        $output = $command->getDisplay(true);

        $this->assertStringContainsString('2 migrations were reverted.', $output);

        $this->assertNotExistsTables('post', 'user');
    }

    public function testExecuteAgain(): void
    {
        $command = $this->getCommand();

        $this->assertEquals(ExitCode::UNSPECIFIED_ERROR, $command->execute([]));

        $output = $command->getDisplay(true);

        $this->assertStringContainsString('Apply a new migration to run this command.', $output);
    }

    private function getCommand(): CommandTester
    {
        return new CommandTester(
            $this->getApplication()->find('migrate/down')
        );
    }
}
