<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\SudokuBundle\Controller;

use Cleentfaar\SudokuBundle\Sudoku\Solver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package Cleentfaar\SudokuBundle\Controller
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
        $solver = new Solver();
        $grid = $this->getRequest()->get('grid');
        $solvedGrid = $solver->solve($grid);
        $solvedCellKeys = array_diff($grid,$solvedGrid);
        $boxColors = $solver->generateBoxColors();
        return $this->render('CleentfaarSudokuBundle:Default:index.html.twig',array('grid'=>$solvedGrid,'solvedCellKeys'=>$solvedCellKeys,'boxColors'=>$boxColors));
    }

    /**
     * @param int $numberOfClues
     * @return mixed
     */
    public function generateAction($numberOfClues = 17)
    {
        $solver = new Solver();
        $grid = $solver->generate(81, 0, 1999, 9, 9);
        $grid = $solver->removeRandomValuesFromGrid($grid, 81 - $numberOfClues);
        $boxColors = $solver->generateBoxColors();
        return $this->render('CleentfaarSudokuBundle:Default:index.html.twig',array('grid'=>$grid,'boxColors'=>$boxColors));
    }
}
