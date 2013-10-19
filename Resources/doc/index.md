CleentfaarSudokuBundle
======================

## Installation
The bundle comes with a ready-to-go set of pages in the form of some controllers and (Twig) templates. However, you may
choose to only use the core part of this bundle, the actual ``Grid`` and ``GridSolver`` classes, and implement them your
own way. Further instructions on usage will be coming soon, in the meantime I advise you to look at the
``DefaultController`` to see how you can go about doing it yourself.

Like any other bundle using Packagist, to install it, you simple add the necessary package to your composer.json file, like so:

    "require" :  {
        // ...
        "cleentfaar/sudoku-bundle":"dev-master"
    }


## Usage
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

Now you can start using the code, which I will be explaining here in more detail very soon.

If you would still like to get a kick-start and implement all of the Controllers and templates that go with this bundle,
you will need to add the bundle's routing file to your application's global routing.yml, like so:

    # app/config/routing.yml
    cleentfaar_sudoku:
        resource: "@CleentfaarSudokuBundle/Resources/config/routing.yml"
        prefix: /sudoku


Since this bundle comes with some assets like stylesheets, javascripts and some external libraries, you should also
install the bundle's assets by executing the following command in your project:

    $ php app/console assets:install
