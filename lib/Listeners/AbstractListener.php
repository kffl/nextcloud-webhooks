<?php

/**
 * @copyright Copyright (c) 2021 Paweł Kuffel <pawel@kuffel.io>
 *
 * @author Paweł Kuffel <pawel@kuffel.io>
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Webhooks\Listeners;

use OCA\Webhooks\Utils\SignedRequest;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use \OCP\IConfig;

/**
 * Class AbstractListener
 *
 * @package OCA\Webhooks\Listeners
 */
abstract class AbstractListener implements IEventListener {

	/** @var IConfig */
	protected $config;
	protected $endpoint;
	protected $secret;

	public const CONFIG_NAME = "";

	public function __construct(IConfig $config)
	{
		$this->config = $config;
		$this->endpoint = $this->config->getSystemValue(static::CONFIG_NAME);
		$this->secret = $this->config->getSystemValue("webhooks_secret");
	}

	public function handle(Event $event): void {
		if (empty($this->endpoint)) {
			return;
		}

		$dto = $this->handleIncomingEvent($event);
		$dto['eventType'] = get_class($event);

		if (!empty($dto)) {
			$this->sendDto($dto);
		}
	}

	protected function sendDto(array $eventDto): void {
		SignedRequest::sendSignedRequest($eventDto, $this->secret, $this->endpoint);
	}

	abstract public function handleIncomingEvent(Event $event);
}