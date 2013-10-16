<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\SudokuBundle\Sudoku;

/**
 * Class Solver
 * @package Cleentfaar\SudokuBundle\Sudoku
 */
class Solver
{
    public function generate($numberOfClues = 17, $attempts = 0, $maxAttempts = 100, $rowTotal = 9, $columnTotal = 9)
    {
        /**
         * Generate the grid
         */
        $grid = array();
        for ($rowNumber = 1; $rowNumber <= $rowTotal; $rowNumber++) {
            for ($columnNumber = 1; $columnNumber <= $columnTotal; $columnNumber++) {
                $boxNumber = $this->getBoxFromRowAndColumn($rowNumber, $columnNumber);
                $grid[$columnNumber.'-'.$rowNumber.'-'.$boxNumber] = '';
            }
        }

        /**
         * Distribute the indicated number of clues across the grid, taking care not to break the rules in the process
         */
        $randomCellKeys = array_rand($grid, $numberOfClues);
        foreach ($randomCellKeys as $cellKey) {
            list($column, $row, $box) = explode("-", $cellKey);
            if (!isset($allowedRowValues[$row])) {
                $allowedRowValues[$row] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if (!isset($allowedColumnValues[$column])) {
                $allowedColumnValues[$column] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if (!isset($allowedBoxValues[$box])) {
                $allowedBoxValues[$box] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            $intersecting = array_intersect($allowedRowValues[$row],$allowedColumnValues[$column],$allowedBoxValues[$box]);
            //var_dump($intersecting);
            if (empty($intersecting)) {
                if ($attempts >= $maxAttempts) {
                    throw new \RuntimeException("Maximum amount of attempts reached before giving up (number of clues: $numberOfClues)");
                }
                return $this->generate($numberOfClues, $attempts + 1, $maxAttempts, $rowTotal, $columnTotal);
            }
            $randomValue = array_rand($intersecting,1);
            unset($allowedRowValues[$row][$randomValue]);
            unset($allowedColumnValues[$column][$randomValue]);
            unset($allowedBoxValues[$box][$randomValue]);
            $grid[$cellKey] = $randomValue;
        }
        return $grid;
    }

    public function solve(array $grid, $attempt = 0, $maxAttempts = 200)
    {

        $walker = new GridWalker($grid);
        $grid = $walker->solveGrid();

        return $grid;
    }

    public function removeRandomValuesFromGrid(array $grid, $numberOfValuesToRemove)
    {
        $randomCellKeys = array_rand($grid, $numberOfValuesToRemove);
        if (!is_array($randomCellKeys)) {
            $randomCellKeys = array($randomCellKeys);
        }
        foreach ($randomCellKeys as $cellKey) {
            $grid[$cellKey] = null;
        }
        return $grid;
    }
    public function generateBoxColors() {
        $colors = array();
        $hex = '#CCFFFF';
        list($r,$g,$b) = $this->hexToRgb($hex);
        $rMultiplier = 0.9;
        $gMultiplier = 0.9;
        $bMultiplier = 0.9;
        for ($x = 1; $x <= 9; $x++) {
            $r = $r * $rMultiplier;
            $g = $g * $gMultiplier;
            $b = $b * $bMultiplier;
            if ($r < 1) {
                $rMultiplier = 1.25;
                var_dump('r = '.$r.' g ='.$g.' b = '.$b);
            }
            if ($g < 1) {
                $gMultiplier = 1.25;
                var_dump('r = '.$r.' g ='.$g.' b = '.$b);
            }
            if ($b < 1) {
                $bMultiplier = 1.25;
                var_dump('r = '.$r.' g ='.$g.' b = '.$b);
            }
            $colors[$x] = $this->rgbToHex($r,$g,$b);
        }
        return $colors;
    }
    private function shuffleWithKeys(&$array) {
        $aux = array();
        $keys = array_keys($array);
        shuffle($keys);
        foreach($keys as $key) {
            $aux[$key] = $array[$key];
            unset($array[$key]);
        }
        $array = $aux;
    }
    private function rgbToHex($r,$g,$b)
    {
        $r=dechex($r);
        If (strlen($r)<2)
            $r='0'.$r;

        $g=dechex($g);
        If (strlen($g)<2)
            $g='0'.$g;

        $b=dechex($b);
        If (strlen($b)<2)
            $b='0'.$b;

        return '#' . $r . $g . $b;
    }
    private function hexToRgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }
    private function getBoxFromRowAndColumn($row,$column) {
        if ($row < 4) {
            if ($column < 4) {
                $box = 1;
            } elseif ($column < 7) {
                $box = 2;
            } else {
                $box = 3;
            }
        } elseif ($row < 7) {
            if ($column < 4) {
                $box = 4;
            } elseif ($column < 7) {
                $box = 5;
            } else {
                $box = 6;
            }
        } else {
            if ($column < 4) {
                $box = 7;
            } elseif ($column < 7) {
                $box = 8;
            } else {
                $box = 9;
            }
        }
        return $box;
    }
}