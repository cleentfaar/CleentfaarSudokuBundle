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
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridDiff;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class GridDiffTest
 * @package Cleentfaar\Bundle\SudokuBundle\Tests\Sudoku
 */
class GridDiffTest extends WebTestCase
{
    public function testGetChangedValues()
    {
        $grid1 = new Grid();
        $grid2 = new Grid();
        $grid2->addClues(17);

        $gridDiff = new GridDiff($grid1, $grid2);
        $changedValues = $gridDiff->getChangedValues();
        var_dump($changedValues);
        $this->assertEquals(17, count($changedValues));
    }
}
