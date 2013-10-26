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
    private $allowedColumnValues;

    /**
     * @var array
     */
    private $allowedRowValues;

    /**
     * @var array
     */
    private $allowedBoxValues;

    /**
     * @param null $grid
     */
    public function __construct(array $grid = null) {
        if ($grid === null) {
            /**
             * Generate the grid
             */
            $grid = GridGenerator::generate()->toArray();
        }
        foreach ($grid as $cellKey => $cellValue) {
            $this->set($cellKey, $cellValue);
        }
        $this->grid = $grid;
        $this->detectAllowedValues();
    }

    /**
     *
     */
    private function detectAllowedValues()
    {
        foreach ($this->grid as $cellKey => $cellValue) {
            list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
            if (count($this->getAllowedColumnValues($column)) < 1) {
                $this->setAllowedColumnValues($column, array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9));
            }
            if (count($this->getAllowedRowValues($row)) < 1) {
                $this->setAllowedRowValues($row, array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9));
            }
            if (count($this->getAllowedBoxValues($box)) < 1) {
               $this->setAllowedBoxValues($box, array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9));
            }
        }
    }

    /**
     * @param $cellKey
     * @return null
     */
    public function get($cellKey)
    {
        return isset($this->grid[$cellKey]) ? $this->grid[$cellKey] : null;
    }

    /**
     * @param $cellKey
     * @param $cellValue
     */
    public function set($cellKey, $cellValue)
    {
        $this->grid[$cellKey] = $cellValue;
        list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
        $this->removeAllowedColumnValue($column, $cellValue);
        $this->removeAllowedRowValue($row, $cellValue);
        $this->removeAllowedBoxValue($box, $cellValue);
    }

    /**
     * @param $cellKey
     */
    public function clear($cellKey)
    {
        $previousValue = $this->grid[$cellKey];
        $this->grid[$cellKey] = null;
        $this->synchronize($cellKey, $previousValue);
    }

    private function synchronize($targetCellKey, $targetPreviousValue)
    {
        $rowValues = array();
        $columnValues = array();
        $boxValues = array();
        list($row, $column, $box) = $this->getPositionFromCellKey($targetCellKey);
        foreach ($this->grid as $cellKey => $cellValue) {
            if ($cellValue > 0) {
                list($currentRow, $currentColumn, $currentBox) = $this->getPositionFromCellKey($cellKey);
                if ($currentRow == $row) {
                    $rowValues[] = $cellValue;
                }
                if ($currentColumn == $column) {
                    $columnValues[] = $cellValue;
                }
                if ($currentBox == $box) {
                    $boxValues[] = $cellValue;
                }
            }
        }
        if (!in_array($targetPreviousValue, $rowValues)) {
            $this->addAllowedRowValue($row, $targetPreviousValue);
        }
        if (!in_array($targetPreviousValue, $columnValues)) {
            $this->addAllowedColumnValue($column, $targetPreviousValue);
        }
        if (!in_array($targetPreviousValue, $boxValues)) {
            $this->addAllowedBoxValue($box, $targetPreviousValue);
        }
    }

    /**
     * @return array
     */
    public function getSolvedCells()
    {
        if (!isset($this->solvedCells)) {
            return array();
        }
        return $this->solvedCells;
    }

    public function getMappedBoxes()
    {
        $boxes = array();
        foreach ($this->grid as $cellKey => $cellValue) {
            list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
            $boxes[$row.'-'.$column] = $box;
        }
        return $boxes;
    }

    /**
     * @param $column
     * @return array
     */
    public function getAllowedColumnValues($column)
    {
        return isset($this->allowedColumnValues[$column]) ? $this->allowedColumnValues[$column] : array();
    }

    /**
     * @param $row
     * @return array
     */
    public function getAllowedRowValues($row)
    {
        return isset($this->allowedRowValues[$row]) ? $this->allowedRowValues[$row] : array();
    }

    /**
     * @param $box
     * @return array
     */
    public function getAllowedBoxValues($box)
    {
        return isset($this->allowedBoxValues[$box]) ? $this->allowedBoxValues[$box] : array();
    }

    /**
     * @param $column
     * @param array $values
     */
    public function setAllowedColumnValues($column, array $values)
    {
        $this->allowedColumnValues[$column] = $values;
    }

    /**
     * @param $row
     * @param array $values
     */
    public function setAllowedRowValues($row, array $values)
    {
        $this->allowedRowValues[$row] = $values;
    }

    /**
     * @param $box
     * @param array $values
     */
    public function setAllowedBoxValues($box, array $values)
    {
        $this->allowedBoxValues[$box] = $values;
    }

    /**
     * @param $row
     * @param $value
     */
    public function removeAllowedRowValue($row, $value)
    {
        if (isset($this->allowedRowValues[$row][$value])) {
            unset($this->allowedRowValues[$row][$value]);
        }
    }

    /**
     * @param $column
     * @param $value
     */
    public function removeAllowedColumnValue($column, $value)
    {
        if (isset($this->allowedColumnValues[$column][$value])) {
            unset($this->allowedColumnValues[$column][$value]);
        }
    }

    /**
     * @param $box
     * @param $value
     */
    public function removeAllowedBoxValue($box, $value)
    {
        if (isset($this->allowedBoxValues[$box][$value])) {
            unset($this->allowedBoxValues[$box][$value]);
        }
    }

    /**
     * @param $row
     * @param $value
     */
    public function addAllowedRowValue($row, $value)
    {
        $this->allowedRowValues[$row][$value] = $value;
    }

    /**
     * @param $column
     * @param $value
     */
    public function addAllowedColumnValue($column, $value)
    {
        $this->allowedColumnValues[$column][$value] = $value;
    }

    /**
     * @param $box
     * @param $value
     */
    public function addAllowedBoxValue($box, $value)
    {
        $this->allowedBoxValues[$box][$value] = $value;
    }

    /**
     * @param $cellKey
     * @return array
     * @throws \Exception
     */
    public function getPositionFromCellKey($cellKey)
    {
        $parts = explode("-", $cellKey);
        if (count($parts) !== 3) {
            throw new \Exception("Expected 3 parts to come from cellkey $cellKey, only got ".count($parts));
        }
        return $parts;
    }

    public function getAllowedValuesByCellKey($cellKey)
    {
        list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
        return array_intersect($this->getAllowedColumnValues($column), $this->getAllowedRowValues($row), $this->getAllowedBoxValues($box));
    }

    /**
     * @param $value1
     * @param $value2
     * @return array
     */
    private function comparePossibleValues($value1, $value2)
    {
        list($row1, $column1, $box1) = $this->getPositionFromCellKey($value1);
        list($row2, $column2, $box2) = $this->getPositionFromCellKey($value2);
        $allowedValues1 = array_intersect($this->getAllowedColumnValues($column1), $this->getAllowedRowValues($row1), $this->getAllowedBoxValues($box1));
        $allowedValues2 = array_intersect($this->getAllowedColumnValues($column2), $this->getAllowedRowValues($row2), $this->getAllowedBoxValues($box2));
        if (count($allowedValues1) > count($allowedValues2)) {
            return $allowedValues2;
        } else {
            return $allowedValues1;
        }
    }

    /**
     * @param bool $sortByNumberOfValues
     * @return array
     */
    public function getCellSolutions($sortByNumberOfValues = false)
    {
        $grid = $this->grid;
        $solutions = array();
        uksort($grid, array($this, "comparePossibleValues"));
        foreach ($grid as $cellKey => $cellValue) {
            list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
            $solutions[$cellKey] = array_intersect($this->getAllowedColumnValues($column), $this->getAllowedRowValues($row), $this->getAllowedBoxValues($box));
        }
        return $solutions;
    }

    /**
     * @param bool $sortByNumberOfPossibleValues
     * @return array
     */
    public function getEmptyCells($sortByNumberOfPossibleValues = false)
    {
        $values = array();
        foreach ($this->grid as $cellKey => $cellValue) {
            if ($cellValue < 1) {
                $values[$cellKey] = $cellValue;
            }
        }
        return $values;
    }

    /**
     * @return array|null
     */
    public function getAllCells()
    {
        return $this->grid;
    }

    /**
     * @return array
     */
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
     * Here we fill in the empty cells that only have one possible value left,
     * making it easier (faster) to determine the other cells
     */
    private function solveSingleSolutionCells()
    {
        foreach ($this->getSingleSolutionCells() as $cellKey => $solution) {
            list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
            $this->set($cellKey, $solution);
        }
    }

    /**
     * @return array
     */
    private function getSingleSolutionCells()
    {
        $singleSolutionCells = array();
        foreach ($this->grid as $cellKey => $cellValue) {
            list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
            $allowedValues = array_intersect($this->getAllowedColumnValues($column), $this->getAllowedRowValues($row), $this->getAllowedBoxValues($box));
            if (count($allowedValues) == 1) {
                $singleSolutionCells[$cellKey] = reset($allowedValues);
            }
        }
        return $singleSolutionCells;
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
            list($row, $column, $box) = $this->getPositionFromCellKey($cellKey);
            $allowedValues = array_intersect($this->getAllowedColumnValues($column), $this->getAllowedRowValues($row), $this->getAllowedBoxValues($box));
            if (empty($allowedValues)) {
                if ($attempts >= $maxAttempts) {
                    throw new \RuntimeException("Maximum amount of attempts reached before giving up (number of clues: $numberOfClues)");
                }
                return $this->addClues($numberOfClues, $attempts + 1, $maxAttempts);
            }
            $randomValue = array_rand($allowedValues,1);
            $this->set($cellKey, $randomValue);
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
     * @return array|null
     */
    public function toArray() {
        return $this->grid;
    }
}
