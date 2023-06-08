<?php

declare(strict_types=1);

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
namespace OCA\Webhooks\Utils;

use OCA\WorkflowEngine\Entity\File;
use OCP\Files\Node;
use OCP\IUser;
use OCP\SystemTag\MapperEvent;

class DtoExtractor {

	public static function buildUserDto(IUser $user) {
		return array(
			'id' => $user->getUID(),
			'displayName' => $user->getDisplayName(),
			'lastLogin' => $user->getLastLogin(),
			'home' => $user->getHome(),
			'emailAddress' => $user->getEMailAddress(),
			'cloudId' => $user->getCloudId(),
			'quota' => $user->getQuota(),
		);
	}

	public static function buildWorkflowFileDto(File $file) {
		return array(
			'displayText' => $file->getDisplayText(),
			'url' => $file->getUrl(),
		);
	}

	public static function buildNodeDto(Node $node) {
		return array(
			'id' => $node->getId(),
			'storage' => $node->getStorage(),
			'path' => $node->getPath(),
			'internalPath' => $node->getInternalPath(),
			'modifiedTime' => $node->getMTime(),
			'mimeType' => $node->getMimetype(),
			'size' => $node->getSize(),
			'Etag' => $node->getEtag(),
			'permissions' => $node->getPermissions(),
			'isUpdateable' => $node->isUpdateable(),
			'isDeletable' => $node->isDeletable(),
			'isShareable' => $node->isShareable(),
		);
	}

	public static function buildMapperEventDto(MapperEvent $event) {
		return array(
			'eventName' => $event->getEvent(),
			'objectType' => $event->getObjectType(),
			'objectId' => $event->getObjectId(),
			'tags' => $event->getTags(),
		);
	}
}