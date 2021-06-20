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
namespace OCA\Webhooks\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IInitialStateService;
use OCP\Settings\ISettings;
use OCA\Webhooks\AppInfo\Application;

class Admin implements ISettings {

    /** @var IConfig */
    private $config;

	public function __construct(IConfig $config) {
        $this->config = $config;
	}

	public function getForm(): TemplateResponse {
        $events = Application::getAllConfigNames();
        $activeEvents = array();
        $inactiveEvents = array();

        foreach ($events as $eventName => $configName) {
            $webhookUrl = $this->config->getSystemValue($configName);
            if (empty($webhookUrl)) {
                $inactiveEvents[$eventName] = $configName;
            } else {
                $activeEvents[$eventName] = $webhookUrl;
            }
        }

		return new TemplateResponse(
			'webhooks',
			'admin',
			[
                'secret' => $this->config->getSystemValue('webhooks_secret'),
                'canCurl' => Admin::testCurl(),
                'activeEvents' => $activeEvents,
                'inactiveEvents' => $inactiveEvents,
            ],
			''
		);
	}

	public function getSection(): string {
		return 'security';
	}

	public function getPriority(): int {
		return 200;
	}

    public static function testCurl(): bool {
        $output = null;
        $retCode = null;
        exec('curl --help', $output, $retCode);
        return ($retCode == 0);
    }
}

