<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\SudokuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Sudoku")')->count() > 0);
    }
    public function testGenerate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generate');

        $this->assertTrue($crawler->filter('html:contains("Sudoku")')->count() > 0);
    }
    public function testSolve()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/solve');

        $this->assertTrue($crawler->filter('html:contains("Sudoku")')->count() > 0);
    }
}
