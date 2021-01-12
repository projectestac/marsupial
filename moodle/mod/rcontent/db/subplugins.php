<?php
// See: https://docs.moodle.org/dev/Subplugins
$subplugins = (array) json_decode(file_get_contents(__DIR__ . "/subplugins.json"))->plugintypes;
