<?php

namespace Maschinengeist\Core;

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
}