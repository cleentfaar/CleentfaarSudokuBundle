<?php
/**
 * This file is part of the CleentfaarSudokuBundle package.
 *
 * (c) Cas Leentfaar <http://cleentfaar.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */
namespace Cleentfaar\Bundle\SudokuBundle\Sudoku;

/**
 * Class GridDiff
 * @package Cleentfaar\Bundle\SudokuBundle\Sudoku
 */
class GridStyler
{

    /**
     * @return array
     */
    public function generateBoxColors() {
        $colors = array();
        $hex = '#CCFFFF';
        list($r,$g,$b) = $this->hexToRgb($hex);
        $rMultiplier = 0.9;
        $gMultiplier = 0.9;
        $bMultiplier = 0.9;
        for ($x = 1; $x <= 9; $x++) {
            $r = $r * $rMultiplier;
            $g = $g * $gMultiplier;
            $b = $b * $bMultiplier;
            if ($r < 1) {
                $rMultiplier = 1.25;
            }
            if ($g < 1) {
                $gMultiplier = 1.25;
            }
            if ($b < 1) {
                $bMultiplier = 1.25;
            }
            $colors[$x] = $this->rgbToHex($r,$g,$b);
        }
        return $colors;
    }

    /**
     * @param $r
     * @param $g
     * @param $b
     * @return string
     */
    private function rgbToHex($r,$g,$b)
    {
        $r=dechex($r);
        If (strlen($r)<2)
            $r='0'.$r;

        $g=dechex($g);
        If (strlen($g)<2)
            $g='0'.$g;

        $b=dechex($b);
        If (strlen($b)<2)
            $b='0'.$b;

        return '#' . $r . $g . $b;
    }

    /**
     * @param $hex
     * @return array
     */
    private function hexToRgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb; // returns an array with the rgb values
    }
}