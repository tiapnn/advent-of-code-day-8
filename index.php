<?php
/*
 * Advent of Code - Day 8
 * Test Invisible Geeks
 * Mattia Penna (https://github.com/tiapnn)
 */

$pointsData = file_get_contents("points.txt");
$points = parsePoints($pointsData);

$instructionsData = file_get_contents('instructions.txt');
$instructions = parseInstrucions($instructionsData);

// solution of first part
$steps = followInstructions($points, $instructions);
echo "The instructions were followed in $steps steps. \n";

// solution of second part
$ghostSteps = followGhostInstructions($points, $instructions);
echo "The ghost instructions were followed in $ghostSteps steps.\n";

function followGhostInstructions(array $points, array $instructions)
{
    $entryPoints = array_values(preg_grep("/..A/", array_keys($points)));
    $finalPoints = array_values(preg_grep("/..Z/", array_keys($points)));

    $coincidences = [];

    foreach ($entryPoints as $epkey) {
        $coincidences[] = followInstructions($points, $instructions, $epkey, $finalPoints);
    }

    return lcmArray($coincidences);
}

function lcm(int $a, int $b): int
{
    return gmp_intval(gmp_lcm($a, $b));
}

function lcmArray(array $arr): int
{
    $result = array_shift($arr);
    foreach ($arr as $number) {
        $result = lcm($result, $number);
    }
    return $result;
}

function followInstructions(array $points, array $instructions, string $entryPoint = "AAA", array $finalPoints = ["ZZZ"]): int
{
    $count = 0;

    $currPoint = $points[$entryPoint];
    $followInstructions = true;

    while ($followInstructions) {

        foreach ($instructions as $direction) {
            $nextPoint = $currPoint[$direction];
            $currPoint = $points[$nextPoint];
            $count++;
            if (in_array($nextPoint, $finalPoints)) {
                $followInstructions = false;
                break;
            }
        }
    }

    return $count;
}

function parseInstrucions(string $data): array
{
    $instructions = [];

    for ($i = 0; $i < strlen($data); $i++) {

        if ($data[$i] == 'L') {
            $instructions[] = 0;
        } elseif ($data[$i] == 'R') {
            $instructions[] = 1;
        }
    }

    return $instructions;
}

function parsePoints(string $data): array
{
    $points = [];
    $lines = explode(PHP_EOL, $data);

    foreach ($lines as $line) {

        list($key, $values) = explode(' = ', $line);
        $values = str_replace(['(', ')'], '', $values);
        $valuesArray = explode(', ', $values);

        $points[$key] = $valuesArray;
    }

    return $points;
}
