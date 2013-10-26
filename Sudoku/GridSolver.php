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
     * @var int
     */
    private $attempt = 0;

    /**
     * @var int
     */
    private $maxAttempts = 199;

    /**
     * @var array
     */
    private $steps = array();

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
        foreach ($this->grid->toArray() as $cellKey => $cellValue) {
            if ($cellValue < 1) {
                list($column, $row, $box) = $this->grid->getPositionFromCellKey($cellKey);
                $allowedValues = array_intersect($this->grid->getAllowedColumnValues($column), $this->grid->getAllowedRowValues($row), $this->grid->getAllowedBoxValues($box));
                if (empty($allowedValues)) {
                    if (!empty($this->steps)) {
                        $this->moveStepBack();
                    } elseif ($this->attempt >= $this->maxAttempts) {
                        throw new \RuntimeException(sprintf("Maximum amount of attempts reached before giving up (number of attempts: %s)", $this->attempt));
                    }
                    return $this->solve();
                }
                $this->moveStepForward($cellKey, $cellValue);
                $allowedValue = array_rand($allowedValues,1);
                $this->grid->set($cellKey, $allowedValue);
            }
        }

        return $this->grid;
    }

    /**
     * @param $cellKey
     * @param $cellValue
     */
    private function moveStepForward($cellKey, $cellValue)
    {
        $this->steps[] = array('cellKey'=>$cellKey, 'cellValue'=>$cellValue);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function moveStepBack()
    {
        if (empty($this->steps)) {
            throw new \Exception("No more steps to move back to");
        }
        $last = array_pop($this->steps);
        $cellKey = $last['cellKey'];
        $cellValue = $last['cellValue'];

        $allowedValues = $this->grid->getAllowedValuesByCellKey($cellKey);
        if (count($allowedValues) <= 1) {
            if (isset($allowedValues[$cellValue]) || count($allowedValues) == 0) {
                $this->grid->clear($cellKey);
                return $this->moveStepBack();
            }
        }
        if (isset($allowedValues[$cellValue])) {
            unset($allowedValues[$cellValue]);
        }
        $allowedValue = array_rand($allowedValues, 1);
        $this->grid->set($cellKey, $allowedValue);
    }
}
