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
 * Class GridWalker
 * @package Cleentfaar\SudokuBundle\Sudoku
 */
class GridWalker {

    /**
     * @var array
     */
    private $grid;

    /**
     * @var array
     */
    private $solutions;

    /**
     * @var int
     */
    private $attempt = 0;

    /**
     * @var int
     */
    private $maxAttempts = 1999;

    /**
     * @param array $grid
     */
    public function __construct(array $grid)
    {
        $this->grid = $grid;
        $this->detectRemainingCells();
        $this->solveSingleSolutionCells();
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function solveGrid()
    {
        $this->attempt++;
        if ($this->attempt >= $this->maxAttempts) {
            throw new \RuntimeException("Maximum attempts reached for solving this grid (".$this->maxAttempts.")");
        }
        foreach ($this->remainingCells as $cellKey => $cellValue) {
            list($column, $row, $box) = explode("-", $cellKey);
            $intersecting = array_intersect($this->allowedRowValues[$row],$this->allowedColumnValues[$column],$this->allowedBoxValues[$box]);
            if (count($intersecting) < 1) {
                /**
                 * No more possibilities, start again?
                 */
                var_dump($this->allowedRowValues[$row]);
                var_dump($this->allowedColumnValues[$column]);
                var_dump($this->allowedBoxValues[$box]);
                throw new \RuntimeException("No more possibilities available for cell $cellKey");
            }
            $this->solutions[$cellKey] = $intersecting;
            $allowedValue = array_rand($intersecting,1);
            $this->grid[$cellKey] = $allowedValue;
            unset($this->allowedRowValues[$row][$allowedValue]);
            unset($this->allowedColumnValues[$column][$allowedValue]);
            unset($this->allowedBoxValues[$box][$allowedValue]);
        }

        return $this->grid;
    }

    /**
     * Detect all remaining cells in the grid, so that we know which ones still need solving
     *
     * At the same time detect the values that have been used already, so that we know which possibilities
     * we have for the remaining empty cells
     *
     * @param array $grid
     * @return array
     */
    private function detectRemainingCells()
    {
        $this->remainingCells = array();
        $this->allowedRowValues = array();
        $this->allowedColumnValues = array();
        $this->allowedBoxValues = array();
        foreach ($this->grid as $cellKey => $value) {
            list($column, $row, $box) = explode("-", $cellKey);
            if (!isset($this->allowedRowValues[$row])) {
                $this->allowedRowValues[$row] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if (!isset($this->allowedColumnValues[$column])) {
                $this->allowedColumnValues[$column] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if (!isset($this->allowedBoxValues[$box])) {
                $this->allowedBoxValues[$box] = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
            }
            if ($value > 0) {
                unset($this->allowedRowValues[$row][$value]);
                unset($this->allowedColumnValues[$column][$value]);
                unset($this->allowedBoxValues[$box][$value]);
            } else {
                $this->remainingCells[$cellKey] = null;
            }
        }
    }

    /**
     * First, we fill in the empty cells that only have one possible value left,
     * making it easier (faster) to determine the other cells
     *
     * @param array $remainingCells
     * @param $allowedRowValues
     * @param $allowedColumnValues
     * @param $allowedBoxValues
     * @return array
     * @throws \RuntimeException
     */
    private function solveSingleSolutionCells()
    {
        foreach ($this->remainingCells as $cellKey => $cellValue) {
            list($column, $row, $box) = explode("-", $cellKey);
            $intersecting = array_intersect($this->allowedRowValues[$row],$this->allowedColumnValues[$column],$this->allowedBoxValues[$box]);
            if (count($intersecting) < 1) {
                /**
                 * No more possibilities, start again?
                 */
                throw new \RuntimeException("No more possibilities available for cell $cellKey");
            } elseif (count($intersecting) == 1) {
                $allowedValue = array_rand($intersecting,1);
                $this->grid[$cellKey] = $allowedValue;
                unset($this->allowedRowValues[$row][$allowedValue]);
                unset($this->allowedColumnValues[$column][$allowedValue]);
                unset($this->allowedBoxValues[$box][$allowedValue]);
                unset($this->remainingCells[$cellKey]);
            }
        }
    }
}