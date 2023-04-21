<?php

namespace Maschinengeist\Core;

use PhpMqtt\Client;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\RepositoryException;

class Helper {

    public static function flattenArrayToMqttTopics(array $inputValues, string $mqttBaseTopic, array &$result) : bool {
        foreach ($inputValues as $key => $value) {

            if (is_array($value)) {
                self::flattenArrayToMqttTopics($value, "$mqttBaseTopic/$key", $result);
                continue;
            }

            # convert php internal types to strings
            if ($value === true)    { $value = 'true';  }
            if ($value === false)   { $value = 'false'; }

            $result["$mqttBaseTopic/$key"] = $value;
        }

        return true;
    }

    public static function logToMqtt(string $errorMessage, Client\MqttClient $mqttClient, $errorTopic) : bool {

        error_log($errorMessage);

        try {
            $mqttClient->publish($errorTopic, $errorMessage, 1, false);
        } catch (DataTransferException|RepositoryException $e) {
            error_log(
                sprintf("Error while transmitting error message via MQTT: %s",
                    $e->getMessage())
            );
        }

        return true;
    }
}