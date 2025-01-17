<?php

/**
 * This file is part of prooph/snapshotter.
 * (c) 2015-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Snapshotter;

use Prooph\Common\Messaging\Message;
use Prooph\EventStore\Projection\ReadModelProjector;

class StreamSnapshotProjection
{
    /**
     * @var ReadModelProjector
     */
    private $readModelProjector;

    public function __construct(ReadModelProjector $readModelProjector, string $streamName)
    {
        $this->readModelProjector = $readModelProjector
            ->fromStream($streamName)
            ->whenAny(function ($state, Message $event): void {
                $this->readModel()->stack('replay', $event);
            });
    }

    public function __invoke(bool $keepRunning = true)
    {
        $this->readModelProjector->run($keepRunning);
    }
}
