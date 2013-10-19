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
 * Class Grid
 * @package Cleentfaar\Bundle\SudokuBundle\Sudoku
 */
class Grid
{

    /**
     * @var array|null
     */
    private $grid = array();

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
     * @param null $grid
     * @param int $columnTotal
     * @param int $rowTotal
     * @param int $numberOfClues
     */
    public function __construct(array $grid = null, $columnTotal = 9, $rowTotal = 9) {
        if ($grid === null) {
            /**
             * Generate the grid
             */
            $this->grid = self::generateArray($columnTotal, $rowTotal);
        } else {
            /**
             * Re-use a given grid, expecting it to be already properly formatted (as above)
             * In most cases, this will be the input array as send by the user, containing the same formatting as above
             * It is therefore important to keep the same naming/values in the actual HTML-elements, e.g.:
             *      <select name="1-2-3">...<option selected="selectes" value="4">4</option>...</select>
             */
            $this->grid = $grid;
        }
    }

    public static function generateArray($columnTotal = 9, $rowTotal = 9)
    {
        $grid = array();
        for ($rowNumber = 1; $rowNumber <= $rowTotal; $rowNumber++) {
            for ($columnNumber = 1; $columnNumber <= $columnTotal; $columnNumber++) {
                $boxNumber = self::getBoxFromRowAndColumn($rowNumber, $columnNumber);
                $grid[$columnNumber.'-'.$rowNumber.'-'.$boxNumber] = '';
            }
        }
        return $grid;
    }

    public function get($cellKey)
    {
        return isset($this->grid[$cellKey]) ? $this->grid[$cellKey] : null;
    }

    public function set($cellKey, $cellValue)
    {
        $this->grid[$cellKey] = $cellValue;
    }

    public function getSolvedCells()
    {
        if (!isset($this->solvedCells)) {
            return array();
        }
        return $this->solvedCells;
    }

    public function getEmptyCells()
    {
        $values = array();
        foreach ($this->grid as $cellKey => $cellValue) {
            if ($cellValue < 1) {
                $values[$cellKey] = $cellValue;
            }
        }
        return $values;
    }

    public function getAllCells()
    {
        return $this->grid;
    }

    public function getNonEmptyCells()
    {
        $values = array();
        foreach ($this->grid as $cellKey => $cellValue) {
            if ($cellValue > 0) {
                $values[$cellKey] = $cellValue;
            }
        }
        return $values;
    }

    /**
     * @param int $numberOfClues
     * @return mixed
     * @throws \RuntimeException
     */
    public function addClues($numberOfClues = 17, $attempts = 0, $maxAttempts = 50)
    {
        /**
         * Distribute the indicated number of clues across the grid, taking care not to break the rules in the process
         */
        $randomCellKeys = array_rand($this->grid, $numberOfClues);
        foreach ($randomCellKeys as $cellKey) {
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
            $intersecting = array_intersect($this->allowedRowValues[$row], $this->allowedColumnValues[$column], $this->allowedBoxValues[$box]);
            if (empty($intersecting)) {
                if ($attempts >= $maxAttempts) {
                    throw new \RuntimeException("Maximum amount of attempts reached before giving up (number of clues: $numberOfClues)");
                }
                return $this->addClues($numberOfClues, $attempts + 1, $maxAttempts);
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
        $randomCellKeys = array_rand($this->getNonEmptyCells(), $numberOfValuesToRemove);
        if (!is_array($randomCellKeys)) {
            $randomCellKeys = array($randomCellKeys);
        }
        foreach ($randomCellKeys as $cellKey) {
            $this->grid[$cellKey] = null;
        }
        return $this->grid;
    }

    /**
     * @param $row
     * @param $column
     * @return int
     */
    public static function getBoxFromRowAndColumn($row,$column) {
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

    /**
     * @return array|null
     */
    public function toArray() {
        return $this->grid;
    }
}
