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

use JuliusHaertl\PHPDocToRst\MainCommand;
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
 * Class CompileGlobalFunctionsDocumentationCommand.
 *
 * @internal Only for use of the phpdoc-to-rst cli tool
 */
class CompileGlobalFunctionsDocumentationCommand extends MainCommand
{
    protected function configure()
    {
        $this->setName('compileglobalfunctions')->
            setDescription('Compile global functions')->
            setHelp('This command makes a compilation of global functions in "src/global_functions" directory to a single class that turns its contents parsable by "generate" command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Início');
        $targetFiles = \scandir("src/global_functions");
        var_dump($targetFiles);
    }
}
