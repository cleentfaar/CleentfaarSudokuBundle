<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\Bundle\SudokuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sudoku/');

        $this->assertTrue($crawler->filter('html:contains("Sudoku")')->count() > 0);
    }
    public function testGenerate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sudoku/generate/30');

        $this->assertTrue($crawler->filter('html:contains("Sudoku")')->count() > 0);
    }
    public function testSolve()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/sudoku/solve', array('grid'=>array('1-1-1'=>1)));

        $this->assertTrue($crawler->filter('html:contains("Sudoku")')->count() > 0);
    }
}
