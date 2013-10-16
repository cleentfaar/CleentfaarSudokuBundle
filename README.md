CleentfaarSudokuBundle
======================

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/cleentfaar/CleentfaarSudokuBundle/badges/quality-score.png?s=919bc082ffe4a529394f4d90c7ee7bdd504f0b46)](https://scrutinizer-ci.com/g/cleentfaar/CleentfaarSudokuBundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/cleentfaar/CleentfaarSudokuBundle/badges/coverage.png?s=c1835c6ec78a1febecbc0ee82cd114e5c2239fd1)](https://scrutinizer-ci.com/g/cleentfaar/CleentfaarSudokuBundle/)
[![Build Status](https://travis-ci.org/cleentfaar/CleentfaarSudokuBundle.png?branch=master)](http://travis-ci.org/cleentfaar/CleentfaarSudokuBundle)

An experimental Symfony bundle which attempts to auto-solve any Sudoku puzzle that it generates.
Currently calculations are done serverside, whilst user interactions (solving and validation) are done by the client.


### Installation & usage ###
For instructions on installing and using this bundle, see the [documentation](Resources/doc/index.md)


### Wait, what? Sudoku? ###
Yeah, so let me explain... it's obviously not meant for any production use but rather an experiment to see how efficient
I can make an algorithm that solves any Sudoku puzzle it is given. As a sidenote: the reason for putting it in a bundle
is merely to get more comfortable with publishing code this way; because Symfony kicks butt.

The idea for this little project started from a [Numberphile video](http://www.youtube.com/watch?v=MlyTq-xVkQE) about
Sudoku puzzles that I watched a while ago. To be clear, the puzzle itself doesn't really interest me, it's much more the
depth of possibilities that arise from changing one single number in the puzzle, not unlike a chess game.
I was wondering how I would go about building a program which solves the most difficult puzzle right in front of your
eyes, if you had the time to waste that is. Just because it would look kind of neat? Yes, well... mostly.

Another part of my interest is about the solving limit of any Sudoku puzzle, which is calculated to require a minimum of
17 clues (check the video above). Overtime I become more and more interested to see if I could make a script that could
actually solve such a puzzle. Bordering on the line of being unsolvable, I want to see it being solved in front of my
eyes (or at least recorded).

I will be facing many issues with this project, probably the biggest issue being that most of the 'solving' mathemetics
on this was eventually calculated on supercomputers by the different research groups. This may mean that I will end up
being able to solve a Sudoku quicker by hand then with my script, which would obviously be ridiculous.
Another issue is going to be the many recursive iterations that will have to be done; keeping track of where you are in
each recursion will be a challenge to keep a sane head over, and memory usage low.
Many of the attempts of the algorithm may end up in a dead end, and in those cases the script will have to know to which
step it has to go back to try another value, until it has finally tried all variations and must give up. But that
should only happen if it really is an unsolvable puzzle (incorrect clues or less then 17 clues).

By the way, don't bother notifying me about other libraries that do this already, for me this is all just a experimental
project to see if I can find the best solving algorithm. There are probably many already out there, it's just that I
like to face and tackle the many problems myself first.


### Coming soon ###
I will be improving the game's grid itself both visually and technically, this may involve adding more interface
elements so that you can really customize the game, and also get hints on the next square to solve, or even let the
scripts attempt to solve the puzzle themselves in front of your eyes!

The latter is the ultimate goal of this script, and an important part of it will be displaying to the user each step
that the computer needs to take to calculate the final solution. Obviously, doing this real-time would often be too fast
for the user to see what's going on, so I will be adding a way to decrease calculation speed (basically: enabling more
and more of the sleep-commands throughout the scripts).
