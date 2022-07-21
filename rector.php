<?php declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector;

return static function (RectorConfig $config): void {
    $config->services()->set(AddClosureReturnTypeRector::class);
    $config->services()->set(FinalizeClassesWithoutChildrenRector::class);
    $config->import(SetList::CODE_QUALITY);
    $config->import(SetList::PHP_74);

    $config->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $config->phpVersion(PhpVersion::PHP_74);
};
