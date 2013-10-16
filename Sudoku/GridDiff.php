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
 * Class GridDiff
 * @package Cleentfaar\SudokuBundle\Sudoku
 */
class GridDiff
{

    /**
     * @var Grid
     */
    private $grid1;

    /**
     * @var Grid
     */
    private $grid2;

    /**
     * @var array
     */
    private $solvedKeys = array();

    /**
     * @param null $grid
     * @param int $columnTotal
     * @param int $rowTotal
     * @param int $numberOfClues
     */
    public function __construct(Grid $grid1, Grid $grid2) {
        $this->grid1 = $grid1;
        $this->grid2 = $grid2;
    }

    /**
     * @return array
     */
    public function getSolvedKeys()
    {
        $grid1Array = $this->grid1->toArray();
        $grid2Array = $this->grid2->toArray();
        return array_diff($grid1Array, $grid2Array);
    }

    /**
     * @param int $numberOfClues
     * @return mixed
     * @throws \RuntimeException
     */
    public function parseGrid($numberOfClues = 17, $attempts = 0, $maxAttempts = 50)
    {
        /**
         * Distribute the indicated number of clues across the grid, taking care not to break the rules in the process
         */
        $randomCellKeys = array_rand($this->grid, $numberOfClues);
        foreach ($randomCellKeys as $cellKey) {
            list($column, $row, $box) = explode("-", $cellKey);
            if (!isset($allowedRowValues[$row])) {
                $this->allowedRowValues[$row] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if (!isset($allowedColumnValues[$column])) {
                $this->allowedColumnValues[$column] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if (!isset($allowedBoxValues[$box])) {
                $this->allowedBoxValues[$box] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            $intersecting = array_intersect($this->allowedRowValues[$row], $this->allowedColumnValues[$column], $this->allowedBoxValues[$box]);
            if (empty($intersecting)) {
                if ($attempts >= $maxAttempts) {
                    throw new \RuntimeException("Maximum amount of attempts reached before giving up (number of clues: $numberOfClues)");
                }
                return $this->parseGrid($numberOfClues, $attempts + 1, $maxAttempts);
            }
            $randomValue = array_rand($intersecting,1);
            unset($this->allowedRowValues[$row][$randomValue]);
            unset($this->allowedColumnValues[$column][$randomValue]);
            unset($this->allowedBoxValues[$box][$randomValue]);
            $this->grid[$cellKey] = $randomValue;
        }
    }

    /**
     * @param int $numberOfValuesToRemove
     * @return mixed
     */
    public function removeRandomValues($numberOfValuesToRemove)
    {
        $randomCellKeys = array_rand($this->grid, $numberOfValuesToRemove);
        if (!is_array($randomCellKeys)) {
            $randomCellKeys = array($randomCellKeys);
        }
        foreach ($randomCellKeys as $cellKey) {
            $this->grid[$cellKey] = null;
        }
        return $this->grid;
    }

    /**
     * @return array
     */
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
            }
            if ($g < 1) {
                $gMultiplier = 1.25;
            }
            if ($b < 1) {
                $bMultiplier = 1.25;
            }
            $colors[$x] = $this->rgbToHex($r,$g,$b);
        }
        return $colors;
    }

    /**
     * @param $r
     * @param $g
     * @param $b
     * @return string
     */
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

    /**
     * @param $hex
     * @return array
     */
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
        return $rgb; // returns an array with the rgb values
    }

    /**
     * @param $row
     * @param $column
     * @return int
     */
    private static function getBoxFromRowAndColumn($row,$column) {
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