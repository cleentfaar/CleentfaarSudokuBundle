<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\Bundle\SudokuBundle\Sudoku;

/**
 * Class GridSolver
 * @package Cleentfaar\Bundle\SudokuBundle\Sudoku
 */
class GridSolver {

    /**
     * @var Grid
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
     * @var array
     */
    private $allowedColumnValues = array();

    /**
     * @var array
     */
    private $allowedRowValues = array();

    /**
     * @var array
     */
    private $allowedBoxValues = array();

    /**
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     * @throws \RuntimeException
     */
    public function solve()
    {
        $this->attempt++;
        if ($this->attempt == 1) {
            $this->scanCells();
            $this->solveSingleSolutionCells();
        } elseif ($this->attempt >= $this->maxAttempts) {
            throw new \RuntimeException("Maximum attempts reached for solving this grid (".$this->maxAttempts.")");
        }
        foreach ($this->grid->getEmptyCells() as $cellKey => $cellValue) {
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
            $this->grid->set($cellKey, $allowedValue);
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
     */
    private function scanCells()
    {
        $this->remainingCells = array();
        $this->allowedRowValues = array();
        $this->allowedColumnValues = array();
        $this->allowedBoxValues = array();
        foreach ($this->grid->getAllCells() as $cellKey => $value) {
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
     * Here we fill in the empty cells that only have one possible value left,
     * making it easier (faster) to determine the other cells
     *
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
                $this->grid->set($cellKey, $allowedValue);
                unset($this->allowedRowValues[$row][$allowedValue]);
                unset($this->allowedColumnValues[$column][$allowedValue]);
                unset($this->allowedBoxValues[$box][$allowedValue]);
                unset($this->remainingCells[$cellKey]);
            }
        }
    }
}
