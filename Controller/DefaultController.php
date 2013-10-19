<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\Bundle\SudokuBundle\Controller;

use Cleentfaar\Bundle\SudokuBundle\Sudoku\Grid;
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridDiff;
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridSolver;
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridStyler;
use Cleentfaar\Bundle\SudokuBundle\Sudoku\Solver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package Cleentfaar\Bundle\SudokuBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {
        return $this->forward('CleentfaarSudokuBundle:Default:generate', array());
    }

    /**
     * @return mixed
     */
    public function solveAction()
    {
        $gridInput = $this->getRequest()->get('grid');
        $grid = new Grid($gridInput);

        $solver = new GridSolver($grid);
        $solvedGrid = $solver->solve();

        $diff = new GridDiff($grid, $solvedGrid);
        $solvedCellKeys = $diff->getChangedValues();

        $styler = new GridStyler();
        $boxColors = $styler->generateBoxColors();

        return $this->render('CleentfaarSudokuBundle:Default:index.html.twig',array('grid'=>$solvedGrid,'solvedCellKeys'=>$solvedCellKeys,'boxColors'=>$boxColors));
    }

    /**
     * @param int $numberOfClues
     * @return mixed
     */
    public function generateAction($numberOfClues)
    {
        $grid = new Grid();
        $grid->addClues($numberOfClues);

        $styler = new GridStyler();
        $boxColors = $styler->generateBoxColors();

        return $this->render('CleentfaarSudokuBundle:Default:index.html.twig',array('grid'=>$grid->toArray(),'boxColors'=>$boxColors));
    }
}
