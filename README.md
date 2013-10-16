CleentfaarSudokuBundle
======================

An experimental Symfony bundle which attempts to auto-solve any Sudoku puzzle that it generates.
Currently built with both PHP and Javascript versions, to experiment with different pro's and con's


### Sudoku huh? ###
Yeah... it's obviously not meant for any production use but rather an experiment to see how efficient I can make an
algorithm that solves any Sudoku puzzle you give it. The reason for putting it in a bundle is merely to get more
comfortable with publishing code this way; because Symfony kicks butt.

The idea for this little project started from a Numberphile video I watched a while ago about Sudoku puzzles.
To be clear, the puzzle itself doesn't really interest me, it's much more the depth of possibilities that arise from
changing one single number in the puzzle, and how one would implement such depth in a PHP implementation or Javascript.
In a way it is much like programming a chess computer, though the variables are much clearer in the case of Sudoku.

Another part of my interest is about the solving limit of any Sudoku puzzle, which is calculated to require a minimum of
17 clues. Overtime I become more and more interested to see if I could make a script that could actually solve such a
puzzle. Bordering on the line of being unsolvable, I want to see it being solved in front of my eyes (or at least recorded).

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
script attempt to solve the puzzle itself!

The latter is the ultimate goal of this script, and an important part of it will be displaying to the user each step
that the computer needs to take to calculate the final solution. Obviously, doing this real-time would be too fast for
the user to see what's going on, so I will be adding a way to increase calculation speed (basically: ignoring more and
more of the sleep-commands throughout the script).
