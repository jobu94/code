<?php

$handle = fopen("testInput.txt", "r");

$boards_marks = array();
$takenNumbers = array();
$bingoCard = array();
$bingoCounter = 0;

$takenNumbers = explode(',', preg_replace("/\r|\n/", "", fgets($handle)));

while (($line = fgets($handle)) !== false) {
    $line = preg_replace("/\r|\n/", "", $line);

    if(empty($line)) {
        $bingoCounter++;
    } else {
        if(!array_key_exists($bingoCounter, $bingoCard)) {
            $bingoCard[$bingoCounter] = array(); //
        }

        $rowValues = explode(' ', trim($line));
        $rowValues = array_filter($rowValues, "is_numeric");
        $rowValues = array_values($rowValues);
        $bingoCard[$bingoCounter][] = $rowValues;
    }
}

//var_dump($bingoCounter);
//var_dump($takenNumbers);

//
foreach ($bingoCard as $index => $board) {
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            $boards_marks[$index][$i][$j] = 'N';
        }
    }
}

//print_r($bingoCard);
$winning_board = 0;
$won_number = 0;

for ($k = 0; $k < count($takenNumbers); $k++) {
    foreach ($bingoCard as $index => $board) {
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                if ($takenNumbers[$k] == $board[$i][$j] && empty($winning_board)) {
                    $boards_marks[$index][$i][$j] = 'Y';

                    if (checkRowMarked($boards_marks[$index])) {
                        $winning_board = $index;
                        $won_number = $bingoCard[$index][$i][$j];
                        break;
                    }
                }
            }
        }
    }
}

function checkRowMarked($marked_board)
{
    for ($i = 0; $i < 5; $i++) {
        if ($marked_board[$i][0] == 'Y' && $marked_board[$i][1] == 'Y' && $marked_board[$i][2] == 'Y' && $marked_board[$i][3] == 'Y' && $marked_board[$i][4] == 'Y') {
            return true;
        } else
            if ($marked_board[0][$i] == 'Y' && $marked_board[1][$i] == 'Y' && $marked_board[2][$i] == 'Y' && $marked_board[3][$i] == 'Y' && $marked_board[4][$i] == 'Y') {
                return true;
            }
    }
    return false;
}

function getScore($index, $boards_marks, $boards_array)
{
    $sum = 0;
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            if ($boards_marks[$index][$i][$j] == 'N') {
                $sum += $boards_array[$index][$i][$j];
            }
        }
    }

    return $sum;
}

echo "\nWinning Board: $winning_board";
$score = $won_number * getScore($winning_board, $boards_marks, $bingoCard);

echo "\nScore: $score";
