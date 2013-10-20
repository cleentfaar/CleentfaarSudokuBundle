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
        if ($this->attempt >= $this->maxAttempts) {
            throw new \RuntimeException("Maximum attempts reached for solving this grid (".$this->maxAttempts.")");
        }
        foreach ($this->grid as $cellKey => $cellValue) {
            list($column, $row, $box) = $this->grid->getPositionFromCellKey($cellKey);
            $allowedValues = array_intersect($this->grid->getAllowedColumnValues($column), $this->grid->getAllowedRowValues($row), $this->grid->getAllowedBoxValues($box));
            if (empty($allowedValues)) {
                if ($this->attempts >= $this->maxAttempts) {
                    throw new \RuntimeException(sprintf("Maximum amount of attempts reached before giving up (number of clues: %s)", $this->maxAttempts));
                }
                return $this->solve();
            }
            $allowedValue = array_rand($allowedValues,1);
            $this->grid->set($cellKey, $allowedValue);
        }

        return $this->grid;
    }
}
