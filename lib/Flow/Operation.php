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
namespace OCA\Webhooks\Flow;

use OCA\Webhooks\Utils\DtoExtractor;
use OCA\Webhooks\Utils\SignedRequest;
use OCA\WorkflowEngine\Entity\File;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\GenericEvent;
use OCP\Files\Node;
use OCP\IConfig;
use OCP\IURLGenerator;
use OCP\SystemTag\MapperEvent;
use OCP\WorkflowEngine\IOperation;
use Psr\Log\LoggerInterface;
use OCP\WorkflowEngine\IManager as FlowManager;
use OCP\WorkflowEngine\IRuleMatcher;
use Symfony\Component\EventDispatcher\GenericEvent as LegacyGenericEvent;
use UnexpectedValueException;

class Operation implements IOperation {
	
	/** @var IURLGenerator */
	private $urlGenerator;
	/** @var LoggerInterface */
	private $logger;
	/** @var IConfig */
	private $config;

	public function __construct(
		IURLGenerator $urlGenerator,
		LoggerInterface $logger,
		IConfig $config
	) {
		$this->urlGenerator = $urlGenerator;
		$this->logger = $logger;
		$this->config = $config;
	}

	/**
	 * @inheritDoc
	 */
	public function getDisplayName(): string {
		return 'Outgoing webhook';
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): string {
		return 'Triggers an outgoing HTTP POST Webhook';
	}

	/**
	 * @inheritDoc
	 */
	public function getIcon(): string {
		return $this->urlGenerator->imagePath('webhooks', 'app.svg');
	}

	/**
	 * @inheritDoc
	 */
	public function isAvailableForScope(int $scope): bool {
		return $scope === FlowManager::SCOPE_ADMIN;
	}

	/**
	 * @inheritDoc
	 */
	public function validateOperation(string $name, array $checks, string $operation): void {
		// pass
	}

	/**
	 * @inheritDoc
	 */
	public function onEvent(string $eventName, Event $event, IRuleMatcher $ruleMatcher): void {
		error_log(print_r($eventName, true));
		$flows = $ruleMatcher->getFlows(false);
		foreach ($flows as $flow) {
			try {
				$entity = $ruleMatcher->getEntity();
				$entityClass = get_class($entity);
				$eventDto = array("eventType" => $entityClass, "eventName" => $eventName);

				if ($eventName === '\OCP\Files::postRename' || $eventName === '\OCP\Files::postCopy') {
					/** @var Node $node */
					[$oldNode, $node] = $event->getSubject();
					$eventDto['node'] = DtoExtractor::buildNodeDto($node);
				} elseif ($event instanceof GenericEvent || $event instanceof LegacyGenericEvent) {
					/** @var Node $node */
					$node = $event->getSubject();
					$eventDto['node'] = DtoExtractor::buildNodeDto($node);
				} elseif ($event instanceof MapperEvent) {
					$eventDto['mapperEvent'] = DtoExtractor::buildMapperEventDto($event);
				}
				if ($entity instanceof File) {
					$eventDto['workflowFile'] = DtoExtractor::buildWorkflowFileDto($entity);
				}

				$flowOptions = json_decode($flow['operation'], true);
				if (!is_array($flowOptions) || empty($flowOptions)) {
					throw new UnexpectedValueException('Cannot decode operation details');
				}

				$url = trim($flowOptions['url'] ?? '');

				if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
					SignedRequest::sendSignedRequest($eventDto,	$this->config->getSystemValue("webhooks_secret"), $url);
				}

			} catch (UnexpectedValueException $e) {
				continue;
			}
		}
	}
}