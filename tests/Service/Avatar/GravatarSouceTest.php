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

namespace OCA\Mail\Tests\Service\Avatar;

use Exception;
use OCA\Mail\Service\Avatar\GravatarSource;
use OCA\Mail\Tests\TestCase;
use OCP\Http\Client\IClient;
use OCP\Http\Client\IClientService;
use OCP\Http\Client\IResponse;
use PHPUnit_Framework_MockObject_MockObject;

class GravatarSourceTest extends TestCase {

	/** @var IClientService|PHPUnit_Framework_MockObject_MockObject */
	private $clientService;

	/** @var GravatarSource */
	private $source;

	protected function setUp() {
		parent::setUp();

		$this->clientService = $this->createMock(IClientService::class);

		$this->source = new GravatarSource($this->clientService);
	}

	public function testFetchExisting() {
		$email = 'hey@jancborchardt.net';
		$uid = 'john';
		$client = $this->createMock(IClient::class);
		$this->clientService->expects($this->once())
			->method('newClient')
			->willReturn($client);
		$response = $this->createMock(IResponse::class);
		$client->expects($this->once())
			->method('get')
			->with('https://secure.gravatar.com/avatar/2fd3f4d5d762955e5b603794a888fa97?size=128&d=404')
			->willReturn($response);
		$response->expects($this->once())
			->method('getBody')
			->willReturn('data');

		$avatar = $this->source->fetch($email, $uid);

		$this->assertEquals('https://secure.gravatar.com/avatar/2fd3f4d5d762955e5b603794a888fa97?size=128&d=404', $avatar);
	}

	public function testFetchHttpError() {
		$email = 'hey@jancborchardt.net';
		$uid = 'john';
		$client = $this->createMock(IClient::class);
		$this->clientService->expects($this->once())
			->method('newClient')
			->willReturn($client);
		$client->expects($this->once())
			->method('get')
			->with('https://secure.gravatar.com/avatar/2fd3f4d5d762955e5b603794a888fa97?size=128&d=404')
			->willThrowException(new Exception());

		$avatar = $this->source->fetch($email, $uid);

		$this->assertNull($avatar);
	}

}
