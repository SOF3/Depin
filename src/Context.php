<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Generator;
use function spl_object_id;
use function usort;

/**
 * The main API for Depin.
 */
final class Context {
    /** @var list<Provider> */
    private array $providers = [];

    /** @var array<class-string<object>, Provider> */
    private array $typeProviderCache = [];

    /** @var list<Tracker> */
    private array $trackers = [];

    /**
     * Register a provider for the context.
     */
    public function addProvider(Provider $provider) : void {
        $this->providers[] = $provider;
        usort($this->providers, static function(Provider $a, Provider $b) : int {
            $ab = $a->comparePriority($b)->signum;
            $ba = $b->comparePriority($a)->signum;
            if ($ab === ProviderPriority::SPECIFIC && $ba === ProviderPriority::SPECIFIC) {
                return spl_object_id($a) <=> spl_object_id($b);
            }

            if ($ab === ProviderPriority::SPECIFIC) {
                return 0 <=> 1;
            }

            if ($ba === ProviderPriority::SPECIFIC) {
                return 1 <=> 0;
            }

            if ($ab !== 0) {
                return $ab;
            }

            return -$ba;
        });
    }

    /**
     * Register a tracker for the context.
     *
     * Trackers are only used for tracking classes provided by other providers.
     * A provider should not track objects provided by itself.
     */
    public function addTracker(Tracker $tracker) : void {
        $this->trackers[] = $tracker;
    }

    /**
     * Requests a dependency instance for a given scope.
     *
     * @template T of object
     * @param class-string<T> $class
     * @return Generator<mixed, mixed, mixed, T>
     * @throws NoProviderException
     */
    public function request(Scope $scope, string $class) : Generator {
        if (!isset($this->typeProviderCache[$class])) {
            $found = false;

            foreach ($this->providers as $provider) {
                if ($provider->supports($class)) {
                    $this->typeProviderCache[$class] = $provider;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                throw new NoProviderException($class);
            }
        }

        $provider = $this->typeProviderCache[$class];
        $object = yield from $provider->provide($this, $scope, $class);
        $this->track($scope, $object);
        return $object;
    }

    /**
     * Pass the object to the trackers.
     * Do not track the same object in the same context twice.
     */
    private function track(Scope $scope, object $object) : void {
        foreach ($this->trackers as $tracker) {
            $tracker->track($scope, $object);
        }
    }
}
