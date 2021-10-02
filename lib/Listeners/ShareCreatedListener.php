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

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Share\Events\ShareCreatedEvent;

/**
 * Class ShareCreatedListener
 *
 * @package OCA\Webhooks\Listeners
 */
class ShareCreatedListener extends AbstractListener implements IEventListener {

	public const CONFIG_NAME = "webhooks_share_created_url";

	public function handleIncomingEvent(Event $event) {
		if (!($event instanceOf ShareCreatedEvent)) {
			return;
		} 

		$share = $event->getShare();

		return array(
			'id' => $share->getId(),
			'fullId' => $share->getFullId(),
			'nodeId' => $share->getNodeId(),
			'nodeType' => $share->getNodeType(),
			'shareType' => $share->getShareType(),
			'sharedWith' => $share->getSharedWith(),
			'sharedWithDisplayName' => $share->getSharedWithDisplayName(),
			'sharedWithAvatar' => $share->getSharedWithAvatar(),
			'permissions' => $share->getPermissions(),
			'status' => $share->getStatus(),
			'note' => $share->getNote(),
			'expirationDate' => $share->getExpirationDate(),
			'label' => $share->getLabel(),
			'sharedBy' => $share->getSharedBy(),
			'shareOwner' => $share->getShareOwner(),
			'token' => $share->getToken(),
			'target' => $share->getTarget(),
			'shareTime' => $share->getShareTime(),
			'mailSend' => $share->getMailSend(),
			'hideDownload' => $share->getHideDownload(),
		);
	}
}