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

    public function testConstruct() {
        $grid = new Grid();
        $gridArray = $grid->toArray();

        $this->assertEquals(81, count($gridArray));
    }

    public function testParseGrid() {
        $gridInput = Grid::generateArray();
        $grid = new Grid($gridInput);
        $gridArrayBefore = $grid->toArray();
        $grid->parseGrid(17);
        $gridArrayAfter = $grid->toArray();

        $difference = array_diff($gridArrayBefore, $gridArrayAfter);
        $this->assertTrue(count($difference) > 0);
    }

    public function testRemoveRandomValues()
    {
        $gridInput = Grid::generateArray();
        $grid = new Grid($gridInput, 81);
        $valuesToRemove = 17;
        $valuesBefore = 0;
        $valuesAfter = 0;
        foreach ($gridInput as $key => $value) {
            if ($value > 0) {
                $valuesBefore++;
            }
        }
        $grid->removeRandomValues($valuesToRemove);
        foreach ($grid->toArray() as $key => $value) {
            if ($value > 0) {
                $valuesAfter++;
            }
        }
        $difference = $valuesBefore - $valuesAfter;
        $this->assertEquals($valuesToRemove, $difference);
    }

}