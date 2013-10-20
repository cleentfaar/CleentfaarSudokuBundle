<?php
namespace Cleentfaar\Bundle\SudokuBundle\Sudoku;

abstract class GridGenerator
{
    public static function generate($numberOfClues = 0)
    {
        $grid = self::generateArray($numberOfClues);
        $gridObject = new Grid($grid);
        if ($numberOfClues > 0) {
            $gridObject->addClues($numberOfClues);
        }
        return $gridObject;
    }

    public static function generateFromArray(array $grid)
    {
        $gridObject = new Grid($grid);
        return $gridObject;
    }

    private static function generateArray($numberOfClues, $columnTotal = 9, $rowTotal = 9)
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
}