<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\Bundle\SudokuBundle\Tests\Sudoku;

use Cleentfaar\Bundle\SudokuBundle\Sudoku\Grid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class GridTest
 * @package Cleentfaar\Bundle\SudokuBundle\Tests\Sudoku
 */
class GridTest extends WebTestCase
{

    public function testConstruct()
    {
        $grid = new Grid();
        $gridArray = $grid->toArray();

        $this->assertEquals(81, count($gridArray));
    }

    public function testParseGrid()
    {
        $gridInput = Grid::generateArray();
        $grid = new Grid($gridInput);
        $gridArrayBefore = $grid->toArray();
        $grid->addClues(17);
        $gridArrayAfter = $grid->toArray();
        $differences = 0;
        foreach ($gridArrayBefore as $cellKey => $cellValue) {
            if ($gridArrayAfter[$cellKey] != $cellValue) {
                $differences++;
            }
        }
        $this->assertEquals(17, $differences);
    }

    public function testRemoveRandomValues()
    {
        $grid = new Grid();
        $grid->addClues(17);
        $valuesToRemove = 17;
        $valuesBefore = count($grid->getNonEmptyCells());
        $grid->removeRandomValues($valuesToRemove);
        $valuesAfter = count($grid->getNonEmptyCells());
        $difference = $valuesBefore - $valuesAfter;
        $this->assertEquals($valuesToRemove, $difference);
    }


    /**
     * @param $row
     * @param $column
     * @return int
     */
    public function testGetBoxFromRowAndColumn() {
        $grid = new Grid();

        $row = 2;
        $column = 2;
        $boxShouldBe = 1;
        $box = $grid->getBoxFromRowAndColumn($row, $column);
        $this->assertEquals($boxShouldBe, $box);

        $row = 5;
        $column = 5;
        $boxShouldBe = 5;
        $box = $grid->getBoxFromRowAndColumn($row, $column);
        $this->assertEquals($boxShouldBe, $box);

        $row = 8;
        $column = 8;
        $boxShouldBe = 9;
        $box = $grid->getBoxFromRowAndColumn($row, $column);
        $this->assertEquals($boxShouldBe, $box);
    }

    public function testToArray()
    {
        $grid = new Grid();
        $array = $grid->toArray();

        $this->assertEquals('array', gettype($array));
    }

}