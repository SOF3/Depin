<?php

declare(strict_types=1);

namespace SOFe\Depin\Example;

use Closure;
use Generator;
use SOFe\Depin;
use function strrpos;
use function substr;
use function yaml_parse_file;

require_once __DIR__ . "/../vendor/autoload.php";

final class Main {
    public static function main() {
        $context = new Depin\Context;
        $context->addProvider(new Depin\SingletonProvider);

        /** @var list<string> $logs */
        $logs = [];
        $collector = function(string $message) use ($logs) {
            $logs[] = $message;
        };
        $context->addProvider(new class($collector) implements Depin\Provider {
            public function __construct(private Closure $collector) {
            }

            public function comparePriority(Depin\Provider $other) : Depin\ProviderPriority {
                return Depin\ProviderPriority::before();
            }

            public function supports(string $class) : bool {
                return $class === Logger::class;
            }

            public function provide(Depin\Context $context, Depin\Scope $scope, string $class) : Generator {
                yield from [];

                /** @var Depin\Metadata\MethodRequester $requester */
                $requester = $scope->getMetadata(Depin\Metadata\MethodRequester::class);

                $context = substr($requester->class, strrpos($requester->class, "\\") + 1);
                $logger = new Logger($context, $this->collector);
                return $logger;
            }
        });

        $rootScope = Depin\Scope::root();
        foreach ([] as $class) {
            $init[] = $context->request($rootScope, $class);
        }
        foreach ([__DIR__ . "/Plugin1/plugin.yml", __DIR__ . "/Plugin2/plugin.yml"] as $file) {
            $manifest = yaml_parse_file($file);

            $name = new PluginName($manifest["name"]);
            $description = new PluginDescription(
                description: $manifest["description"],
                version: $manifest["version"],
            );

            $pluginScope = $rootScope->createChild([
                PluginName::class => $name,
                PluginDescription::class => $description,
            ]);

            $namespace = $manifest["namespace"];
            foreach ($manifest["classes"] as $class) {
                $class = $namespace . "\\" . $class;
                $init[] = $context->request($pluginScope, $class);
            }
        }
    }
}

Main::main();
