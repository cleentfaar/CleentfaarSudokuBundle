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
use Cleentfaar\Bundle\SudokuBundle\Sudoku\GridGenerator;
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
     * @param int $numberOfClues
     * @return mixed
     */
    public function generateAction($numberOfClues = 17)
    {
        $grid = GridGenerator::generate($numberOfClues);

        $styler = new GridStyler();
        $boxColors = $styler->generateBoxColors();

        return $this->render('CleentfaarSudokuBundle:Default:index.html.twig',array('grid'=>$grid->toArray(),'boxColors'=>$boxColors, 'mappedBoxes'=>$grid->getMappedBoxes()));
    }

    /**
     * @return mixed
     */
    public function solveAction()
    {
        $gridInput = $this->getRequest()->get('grid');
        $grid = GridGenerator::generateFromArray($gridInput);

        $solver = new GridSolver($grid);
        $solvedGrid = $solver->solve();

        $diff = new GridDiff($grid, $solvedGrid);
        $solvedCellKeys = $diff->getChangedValues();
        var_dump($solvedGrid);

        $styler = new GridStyler();
        $boxColors = $styler->generateBoxColors();

        return $this->render('CleentfaarSudokuBundle:Default:index.html.twig',array('grid'=>$solvedGrid->toArray(),'solvedCellKeys'=>$solvedCellKeys, 'mappedBoxes'=>$grid->getMappedBoxes(),'boxColors'=>$boxColors));
    }
}
