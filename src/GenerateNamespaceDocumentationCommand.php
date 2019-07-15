<?php
/**
 * @copyright Copyright (c) 2017 Julius Härtl <jus@bitgrid.net>
 * @author    Julius Härtl <jus@bitgrid.net>
 * @license   GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace JuliusHaertl\PHPDocToRst;

use JuliusHaertl\PHPDocToRst\Extension\GithubLocationExtension;
use JuliusHaertl\PHPDocToRst\Extension\NoPrivateExtension;
use JuliusHaertl\PHPDocToRst\Extension\PublicOnlyExtension;
use JuliusHaertl\PHPDocToRst\Extension\TocExtension;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateNamespaceDocumentationCommand.
 *
 * @internal Only for use of the phpdoc-to-rst cli tool
 */
class GenerateNamespaceDocumentationCommand extends Command
{
    protected function configure()
    {
        $this->setName('generate-ns')->
            setDescription('Generate documentation for a specific namespace')->
            setHelp('This command allows you to generate sphinx/rst based documentation from PHPDoc annotations.')->
            addArgument('ns', InputArgument::REQUIRED, 'Target namespace')->
            addArgument('target', InputArgument::REQUIRED, 'Destination for the generated rst files')->
            addArgument('src', InputArgument::IS_ARRAY, 'Source directories to parse')->
            addOption('public-only', 'p', InputOption::VALUE_NONE)->
            addOption('show-private', null, InputOption::VALUE_NONE)->
            addOption('element-toc', 't', InputOption::VALUE_NONE)->
            addOption('clear', 'c', InputOption::VALUE_NONE, 'Clear the target directory before generate the documentation')->
            addOption('repo-github', null, InputOption::VALUE_REQUIRED, 'Github URL of the projects git repository (requires --repo-base as well)', false)->
            addOption('repo-base', null, InputOption::VALUE_REQUIRED, 'Base path of the project git repository', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $src = $input->getArgument('src');
        $dst = $input->getArgument('target');
        $ns = $input->getArgument('ns');
        

        $apiDocBuilder = new ApiDocBuilder($src, $dst);
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $apiDocBuilder->setVerboseOutput(true);
        }
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
            $apiDocBuilder->setVerboseOutput(true);
            $apiDocBuilder->setDebugOutput(true);
        }
        if ($input->getOption('public-only')) {
            $apiDocBuilder->addExtension(PublicOnlyExtension::class);
        }
        if (!$input->getOption('show-private')) {
            $apiDocBuilder->addExtension(NoPrivateExtension::class);
        }
        if ($input->getOption('element-toc')) {
            $apiDocBuilder->addExtension(TocExtension::class);
        }

        if ($input->getOption('repo-github') && $input->getOption('repo-base')) {
            $apiDocBuilder->addExtension(GithubLocationExtension::class, [
                $input->getOption('repo-base'),
                $input->getOption('repo-github'),
            ]);
        }

        if ($input->getOption('clear')) {
            $this->clearDirectory($dst);
        }


        $apiDocBuilder->build();
        $this->moveNamespaceDirectory($dst, $ns);
        $this->mergeStaticContent($dst);
    }





    protected function clearDirectory(string $dir) : void
    {
        $dirIt = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $recIt = new \RecursiveIteratorIterator($dirIt, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($recIt as $file) {
            (($file->isDir() === true) ? rmdir($file->getRealPath()) : unlink($file->getRealPath()));
        }
    }


    protected function copyDirectory(string $source, string $dest) : void
    {
        $isOK = true;
        // Apenas se trata-se de um diretório real.
        if (is_dir($source) === true) {
            // Se o diretório de destino não existir, cria-o
            if (is_dir($dest) === false) {
                $isOK = mkdir($dest);
            }
            // Se está ok
            if ($isOK === true) {
                // Itera os itens do diretório.
                $dir = scandir($source);
                foreach ($dir as $key => $fileName) {
                    if (in_array($fileName, [".", ".."]) === false && $isOK === true) {
                        $origin = $source . "/" . $fileName;
                        $target = $dest . "/" . $fileName;
                        // Se for um diretório
                        if (is_dir($origin) === true) {
                            $this->copyDirectory($origin, $target);
                        } else {
                            copy($origin, $target);
                        }
                    }
                }
            }
        }
    }


    protected function moveNamespaceDirectory(string $destinationPath, string $namespace) : void
    {
        $tgtNSPath = rtrim($destinationPath, '\\/') . '/' . rtrim($namespace, '\\/');
        if (is_dir($tgtNSPath) === true) {
            rename($tgtNSPath, '_tmpNSDirectory');
            $this->clearDirectory($destinationPath);
            rmdir($destinationPath);
            rename('_tmpNSDirectory', $destinationPath);
        }
    }


    protected function mergeStaticContent(string $destinationPath) : void
    {
        $staticContent = __DIR__.DIRECTORY_SEPARATOR.'_static';
        $this->copyDirectory($staticContent, $destinationPath);
    }
}
