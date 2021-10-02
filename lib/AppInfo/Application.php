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
namespace OCA\Webhooks\AppInfo;

use OCA\Webhooks\Listeners\CalendarObjectUpdatedListener;
use OCA\Webhooks\Listeners\UserLiveStatusListener;
use OCA\Webhooks\Listeners\LoginFailedListener;
use OCA\Webhooks\Listeners\PasswordUpdatedListener;
use OCA\Webhooks\Listeners\ShareCreatedListener;
use OCA\Webhooks\Listeners\UserChangedListener;
use OCA\Webhooks\Listeners\UserCreatedListener;
use OCA\Webhooks\Listeners\UserDeletedListener;
use OCA\Webhooks\Listeners\UserLoggedInListener;
use OCA\Webhooks\Listeners\UserLoggedOutListener;

use OCA\DAV\Events\CalendarObjectUpdatedEvent;
use OCA\Webhooks\Flow\RegisterFlowOperationsListener;
use OCP\Authentication\Events\LoginFailedEvent; 
use OCP\Share\Events\ShareCreatedEvent;
use OCP\User\Events\UserChangedEvent;
use OCP\User\Events\UserCreatedEvent;
use OCP\User\Events\UserDeletedEvent;
use OCP\User\Events\UserLoggedInEvent;
use OCP\User\Events\UserLoggedOutEvent;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\User\Events\PasswordUpdatedEvent;
use OCP\User\Events\UserLiveStatusEvent;
use OCP\WorkflowEngine\Events\RegisterOperationsEvent;

/**
 * Class Application
 *
 * @package OCA\Webhooks\AppInfo
 */
class Application extends App implements IBootstrap {

	public function __construct() {
		parent::__construct('webhooks');
	}

	public function register(IRegistrationContext $context):void {
		$context->registerEventListener(CalendarObjectUpdatedEvent::class, CalendarObjectUpdatedListener::class);
		$context->registerEventListener(LoginFailedEvent::class, LoginFailedListener::class);
		$context->registerEventListener(PasswordUpdatedEvent::class, PasswordUpdatedListener::class);
		$context->registerEventListener(ShareCreatedEvent::class, ShareCreatedListener::class);
		$context->registerEventListener(UserChangedEvent::class, UserChangedListener::class);
		$context->registerEventListener(UserCreatedEvent::class, UserCreatedListener::class);
		$context->registerEventListener(UserDeletedEvent::class, UserDeletedListener::class);
		$context->registerEventListener(UserLiveStatusEvent::class, UserLiveStatusListener::class);
		$context->registerEventListener(UserLoggedInEvent::class, UserLoggedInListener::class);
		$context->registerEventListener(UserLoggedOutEvent::class, UserLoggedOutListener::class);

		$context->registerEventListener(RegisterOperationsEvent::class, RegisterFlowOperationsListener::class);
	}

	public function boot(IBootContext $context): void {}

	public static function getAllConfigNames() {
		return array(
			"Calendar Object Updated" => CalendarObjectUpdatedListener::CONFIG_NAME,
			"Login Failed" => LoginFailedListener::CONFIG_NAME,
			"Password Updated" => PasswordUpdatedListener::CONFIG_NAME,
			"Share Created" => ShareCreatedListener::CONFIG_NAME,
			"User Changed" => UserChangedListener::CONFIG_NAME,
			"User Created" => UserCreatedListener::CONFIG_NAME,
			"User Deleted" => UserDeletedListener::CONFIG_NAME,
			"User Live Status" => UserLiveStatusListener::CONFIG_NAME,
			"User Logged In" => UserLoggedInListener::CONFIG_NAME,
			"User Logged Out" => UserLoggedOutListener::CONFIG_NAME,
		);
	}
}