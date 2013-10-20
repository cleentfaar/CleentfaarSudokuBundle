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
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridGenerator;
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridSolver;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class GridSolverTest
 * @package Cleentfaar\Bundle\SudokuBundle\Tests\Sudoku
 */
class GridSolverTest extends WebTestCase
{
    public function testSolve()
    {

        $gridInput = GridGenerator::generate(17);
        $grid = new Grid($gridInput->toArray());

        $solver = new GridSolver($grid);
        $solvedGrid = $solver->solve();

        $this->assertTrue($solvedGrid instanceof Grid);
    }
}
