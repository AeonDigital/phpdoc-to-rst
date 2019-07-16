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
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class ConfigDocumentationCommand.
 *
 * @internal Only for use of the phpdoc-to-rst cli tool
 */
class ConfigDocumentationCommand extends MainCommand
{
    protected function configure()
    {
        $this->setName('config')->
            setDescription('Configure the conf.py')->
            setHelp('This command allows you to configure the "conf.py" file before create the documentation usin the "sphinx-build" command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('This tutorial will help you to configure the "conf.py" that will be used with the "sphinx-build" command.');
        $output->writeln('Use whenever your project information changes.');
        $output->writeln('**Use only ANSI characters.**');
        $output->writeln('');
        $output->writeln('');



        $output->writeln('Please enter the information for: ');
        $qst_Name           = new Question('1 - Project name : ',           'ProjectName');
        $qst_Description    = new Question('2 - Project description : ',    '');
        $qst_Company        = new Question('3 - Company : ',                '');
        $qst_Publisher      = new Question('4 - Publisher : ',              '');
        $qst_Author         = new Question('5 - Author : ',                 '');
        $qst_Contributors   = new Question('6 - Contributors : ',           '');
        $qst_Locale         = new Question('7 - Locale : ',                 'en-US');
        $qst_Version        = new Question('8 - Version : ',                '');


        $helper = $this->getHelper('question');
        $PROJECT_NAME = $helper->ask($input, $output, $qst_Name);
        $output->writeln('');
        $PROJECT_DESCRIPTION = $helper->ask($input, $output, $qst_Description);
        $output->writeln('');
        $PROJECT_COMPANY = $helper->ask($input, $output, $qst_Company);
        $output->writeln('');
        $PROJECT_PUBLISHER = $helper->ask($input, $output, $qst_Publisher);
        $output->writeln('');
        $PROJECT_AUTHOR_NAME = $helper->ask($input, $output, $qst_Author);
        $output->writeln('');
        $PROJECT_CONTRIBUTOR = $helper->ask($input, $output, $qst_Contributors);
        $output->writeln('');
        $PROJECT_PHPDOC_LOCALE = $helper->ask($input, $output, $qst_Locale);
        $output->writeln('');
        $PROJECT_VERSION = $helper->ask($input, $output, $qst_Version);
        $output->writeln('');

        $now = new \DateTime();
        $PROJECT_YEAR = $now->format('Y');

        $PROJECT_PHPDOC_LANGUAGE = 'en';
        if (strlen($PROJECT_PHPDOC_LOCALE) === 5) {
            $splitLocale = explode('-', $PROJECT_PHPDOC_LOCALE);
            if (count($splitLocale) === 2) {
                $PROJECT_PHPDOC_LANGUAGE = strtolower($splitLocale[0]);
            }
        }



        $templateData = [
            'PROJECT_NAME', 'PROJECT_DESCRIPTION', 'PROJECT_YEAR', 'PROJECT_COMPANY', 
            'PROJECT_PUBLISHER', 'PROJECT_AUTHOR_NAME', 'PROJECT_CONTRIBUTOR', 
            'PROJECT_PHPDOC_LOCALE', 'PROJECT_PHPDOC_LANGUAGE', 'PROJECT_VERSION'
        ];
        $rawTemplate = file_get_contents(__DIR__ . '/_static/template-conf.py');

        $confpy = $rawTemplate;
        foreach ($templateData as $varName) {
            $confpy = str_replace('[['.$varName.']]', $$varName, $confpy);
        }
        file_put_contents(__DIR__ . '/_static/conf.py', $confpy);
    }
}
