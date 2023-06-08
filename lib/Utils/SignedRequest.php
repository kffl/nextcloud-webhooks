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

class SignedRequest {

	public static function sendSignedRequest(array $eventDto, $secret, $endpoint) {
		$eventJSON = json_encode($eventDto);
		$bodyHash = hash('sha256', $eventJSON . $secret);
		$eventJSONescaped = escapeshellarg($eventJSON);
		$endpointEscaped = escapeshellarg($endpoint);

		$curl  = "curl $endpointEscaped --header \"X-Nextcloud-Webhooks: $bodyHash\" ";
		$curl .= "--header \"Content-Type: application/json\" --request POST ";
		$curl .= "--data $eventJSONescaped  > /dev/null 2>&1 &";

		exec($curl);
	}
}