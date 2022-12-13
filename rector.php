<?php declare(strict_types=1);

use function MLL\RectorConfig\config;

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\Rector\FuncCall\FuncCallToNewRector;
use Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector;

return static function (RectorConfig $rectorConfig): void {
    config($rectorConfig);

    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    $rectorConfig->ruleWithConfiguration(FuncCallToNewRector::class, ['collect' => 'Illuminate\\Support\\Collection']);
    $rectorConfig->rule(AddClosureReturnTypeRector::class);

    $rectorConfig->import(SetList::CODE_QUALITY);
    $rectorConfig->import(SetList::PHP_74);
};
