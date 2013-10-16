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

        $crawler = $client->request('GET', '/generate/30');

        $this->assertEquals(30, $crawler->filter('.input option[value!=\'\']:checked')->count());
    }
    public function testSolve()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/solve', array('grid'=>array('1-1-1'=>1)));

        $this->assertEquals(1, $crawler->filter('html:contains(\'id="1-1-1"\')')->count());
        $this->assertEquals(81, $crawler->filter('.cell')->count());
    }
}
