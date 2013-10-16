<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * AppTestUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class AppTestUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        // cleentfaar_sudoku
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'cleentfaar_sudoku');
            }

            return array (  '_controller' => 'Cleentfaar\\SudokuBundle\\Controller\\DefaultController::indexAction',  '_route' => 'cleentfaar_sudoku',);
        }

        // cleentfaar_sudoku_solve
        if ($pathinfo === '/solve') {
            return array (  '_controller' => 'Cleentfaar\\SudokuBundle\\Controller\\DefaultController::solveAction',  '_route' => 'cleentfaar_sudoku_solve',);
        }

        // cleentfaar_sudoku_generate
        if (0 === strpos($pathinfo, '/generate') && preg_match('#^/generate/(?P<numberOfClues>[^/]++)$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'cleentfaar_sudoku_generate')), array (  '_controller' => 'Cleentfaar\\SudokuBundle\\Controller\\DefaultController::generateAction',));
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
