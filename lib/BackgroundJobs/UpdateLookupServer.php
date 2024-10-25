<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2023 Maxence Lange <maxence@artificial-owl.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\GlobalSiteSelector\BackgroundJobs;

use OCA\GlobalSiteSelector\GlobalSiteSelector;
use OCA\GlobalSiteSelector\Slave;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\IJob;
use OCP\BackgroundJob\TimedJob;
use OCP\IConfig;

class UpdateLookupServer extends TimedJob {


	public function __construct(
		ITimeFactory $time,
		IConfig $config,
		private GlobalSiteSelector $globalSiteSelector,
		private Slave $slave
	) {
		parent::__construct($time);

		$this->setInterval($config->getSystemValueInt('gss.updatels.interval', 86400));
		$this->setTimeSensitivity(IJob::TIME_SENSITIVE);
	}

	protected function run($argument) {
		if (!$this->globalSiteSelector->isSlave()) {
			return;
		}

		$this->slave->batchUpdate();
	}
}
