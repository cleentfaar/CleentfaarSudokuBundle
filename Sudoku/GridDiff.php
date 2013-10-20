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
 * Class GridDiff
 * @package Cleentfaar\Bundle\SudokuBundle\Sudoku
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
     * @param null $grid
     * @param int $columnTotal
     * @param int $rowTotal
     * @param int $numberOfClues
     */
    public function __construct(Grid $grid1, Grid $grid2)
    {
        $this->grid1 = $grid1;
        $this->grid2 = $grid2;
    }

    /**
     * @return array
     */
    public function getChangedValues()
    {
        $cellsGrid1 = $this->grid1->getAllCells();
        $cellsGrid2 = $this->grid2->getAllCells();
        $changedValues = array();
        foreach ($cellsGrid1 as $cellKey => $cellValue) {
            if ($cellValue != $cellsGrid2[$cellKey]) {
                $changedValues[$cellKey] = $cellsGrid2[$cellKey];
            }
        }
        return $changedValues;
    }
}
