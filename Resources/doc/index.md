CleentfaarSudokuBundle
======================

### Installation ###
The bundle comes with a ready-to-go set of pages in the form of some controllers and (Twig) templates. However, you may
choose to only use the core part of this bundle, the actual Grid and GridSolver classes, and implement them your own way.
Further instructions on usage will be coming soon, in the meantime I advise you to look at the DefaultController to see
how you can go about doing it yourself.

Like any other bundle, to install it, you simple add the necessary package to your composer.json file, like so:

    "require" :  {
        // ...
        "cleentfaar/sudoku-bundle":"dev-master"
    }


### Usage ###
To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Cleentfaar\CleentfaarSudokuBundle(),
    );
    // ...
}
```