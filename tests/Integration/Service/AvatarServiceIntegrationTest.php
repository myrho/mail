<?php

/**
 * @copyright 2017 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @author 2017 Christoph Wurst <christoph@winzerhof-wurst.at>
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

namespace OCA\Mail\Tests\Integration\Service;

use OC;
use OCA\Mail\Contracts\IAvatarService;
use OCA\Mail\Tests\TestCase;
use OCP\ICache;
use OCP\ICacheFactory;

class AvatarServiceIntegrationTest extends TestCase {

	/** @var IAvatarService */
	private $service;

	private function clearCache() {
		/* @var $cacheFactory ICacheFactory */
		$cacheFactory = OC::$server->query(ICacheFactory::class);
		/* @var $cache ICache */
		$cache = $cacheFactory->create('mail.avatars');
		$cache->clear();
	}

	protected function setUp() {
		parent::setUp();

		$this->clearCache();
		$this->service = OC::$server->query(IAvatarService::class);
	}

	public function testJansGravatar() {
		$avatar = $this->service->getAvatarUrl('hey@jancborchardt.net', 'john');
		$this->assertEquals('https://secure.gravatar.com/avatar/2fd3f4d5d762955e5b603794a888fa97?size=128&d=404', $avatar);
		$image = $this->service->getAvatarImage('hey@jancborchardt.net', 'john');
		$this->assertNotNull($image);
	}

	public function testJansNicknameFavicon() {
		$avatar = $this->service->getAvatarUrl('janli@nextcloud.com', 'jane');
		$this->assertEquals('https://nextcloud.com/media/screenshot-150x150.png?x16328', $avatar);
		$image = $this->service->getAvatarImage('janli@nextcloud.com', 'jane');
		$this->assertNotNull($image);
	}

}
