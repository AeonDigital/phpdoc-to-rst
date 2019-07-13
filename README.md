# phpdoc-to-rst

Forked from Francesco "Abbadon1334" Danti by AeonDigital.
In this fork the changes has only visual and superficial effects [ just because I'm boring :) ].
Prefer to use the "Abbadon1334" version if you prefer a constantly updated project.



Now working as intended, with good coverage. 

##Generate reStructuredText for Sphinx based documentation from PHPDoc annotations. 

This project is heavily based on [phpDocumentor/Reflection](https://github.com/phpDocumentor/Reflection)
and makes use of [sphinxcontrib-phpdomain](https://github.com/markstory/sphinxcontrib-phpdomain).

An example for the documentation output can be found in our [own documentation](http://phpdoc-to-rst.readthedocs.io/en/latest/api_docs.html)

## Quickstart

Install phpdoc-to-rst to your project directory: 
    
    composer require --dev aeondigital/phpdoc-to-rst
    
Command line usage
-
Run the command line tool to parse the folders containing your PHP tree and render the reStructuredText files to the output directory:

    php vendor/bin/phpdoc-to-rst generate --repo-base "$PWD" --repo-github https://github.com/aeondigital/phpdoc-to-rst -t docs/rst/ src/

Programatically usage to generate documentation rst
-
```PHP

    // your source path or multiple path to be parsed
    $src = [__DIR__.'/../src'];
    
    // destination path for the documentation
    $dst = __DIR__.'/../docs/api';
    
    $apiDocBuilder = new ApiDocBuilder($src, $dst);
    
    // DEBUG FATURES : optional
    // optional : activate verbosity
    $apiDocBuilder->setVerboseOutput(true);
    // optional : activate debug
    $apiDocBuilder->setDebugOutput(true);
    
    // EXTENSIONS : optional
        
    /**
     * Do not render classes marked with phpDoc internal tag
     * Do only render public methods/properties.
     */
    $apiDocBuilder->addExtension(PublicOnlyExtension::class);
        
    /**
     * Do not render classes marked with phpDoc internal tag
     * Do only render public methods/properties.
     */
    $apiDocBuilder->addExtension(NoPrivateExtension::class);
        
    /**
     * This extension will render a list of methods  for easy access
     * at the beginning of classes, interfaces and traits.
     */
    $apiDocBuilder->addExtension(TocExtension::class);
        
    /**
     * This extension adds a link to the source at github to all elements.
     *
     * Arguments
     * 0 => Url to the github repo (required)
     * 1 => Path to the git repository (required)
     * 2 => Branch to link to (default=master)
     */
    $apiDocBuilder->addExtension(GithubLocationExtension::class, [
        __DIR__.'/../src',
        'http://github.com/aeondigital/phpdoc-to-rst/',
    ]);
    
    // Build documentation
    $apiDocBuilder->build();

```
