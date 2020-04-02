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

use Symfony\Component\Console\Command\Command;

/**
 * Class MainCommand.
 *
 * @internal Only for use of the phpdoc-to-rst cli tool
 */
class MainCommand extends Command
{
    /**
     * Remove all files and directories from the target directory.
     *
     * @param string $dir Target directory.
     * @return void
     */
    protected function clearDirectory(string $dir) : void
    {
        $dirIt = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $recIt = new \RecursiveIteratorIterator($dirIt, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($recIt as $file) {
            (($file->isDir() === true) ? rmdir($file->getRealPath()) : unlink($file->getRealPath()));
        }
    }


    /**
     * Recursive version of copy command.
     *
     * @param string $source Path to cource content.
     * @param string $dest Path to the destination.
     * @return void
     */
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


    /**
     * After extract the PHPDocs it will get only the given namespace documentation and
     * put it in the destination path. The documentation of another namespaces will be excluded.
     *
     * @param string $destinationPath Path where the reST files will be created.
     * @param string $namespace Target namespace
     * @return void
     */
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


    /**
     * Get the static content provider in `src/_static` directory and merge it with
     * the content of destination files.
     *
     * @param string $destinationPath Path where the reST files will be created.
     * @return void
     */
    protected function mergeStaticContent(string $destinationPath) : void
    {
        $staticContent = __DIR__.DIRECTORY_SEPARATOR.'_static';
        $this->copyDirectory($staticContent, $destinationPath);

        unlink($destinationPath.DIRECTORY_SEPARATOR.'template-conf.py');
        unlink($destinationPath.DIRECTORY_SEPARATOR.'index-namespaces-all.rst');
    }



    /**
     * Make some adjusts on `rst`files generated.
     *
     * @param string $destinationPath Path to reparse files.
     * @return void
     */
    protected function reParseRestFiles(string $destinationPath) : void
    {
        $targetFiles = [];
        $dirIt = new \RecursiveDirectoryIterator($destinationPath, \FilesystemIterator::SKIP_DOTS);
        $recIt = new \RecursiveIteratorIterator($dirIt, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($recIt as $file) {
            if ($file->isDir() === false) {
                if (strpos($file->getRealPath(), ".rst") !== false) {
                    $targetFiles[] = $file->getRealPath();
                }
            }
        }


        foreach ($targetFiles as $absoluteFilePath) {
            $fileContent = file_get_contents($absoluteFilePath);
            $fileContent = str_replace("\*\*", "**", $fileContent);

            $fileLines = explode("\n", $fileContent);
            $insideCodeBlock = false;
            foreach ($fileLines as $i => $line) {
                $nline = $line;

                if (strpos($nline, ".. php:namespace::") !== false) {
                    $nline = str_replace("\\", "§", $nline);
                }

                $paramType = [];
                if (preg_match('/\- ‹ ([\S ]+) ›/', $nline, $paramType) === 1) {
                    $escapedType = str_replace("\\", "§", $paramType[1]);
                    $nline = str_replace($paramType[1], $escapedType, $nline);
                }


                $nline = str_replace("\\\\", "§", $nline);
                $nline = str_replace("\\", "", $nline);
                $nline = str_replace("§", "\\", $nline);
                $nline = str_replace("\"", "&#34;", $nline);
                $nline = str_replace("'", "&#39;", $nline);
                $nline = str_replace(":maxdepth: 1", ":maxdepth: 6", $nline);


                $hasCodeBlockMarkup = (strpos($nline, "```") !== false);
                if ($insideCodeBlock === false) {
                    $insideCodeBlock = true;
                } else {
                    $insideCodeBlock = false;
                }


                if (strpos($nline, ":php:class:") !== false ||
                    strpos($nline, ":php:interface:") !== false ||
                    strpos($nline, ":Returns:") !== false) {
                    $nline = str_replace("\\", "\\\\", $nline);
                }

                $nline = str_replace("```", "\`\`\`", $nline);
                $nline = str_replace("``&#39;&#39;``", "``''``", $nline);
                $fileLines[$i] = $nline;
            }


            $fileContent = implode("\n", $fileLines);
            file_put_contents($absoluteFilePath, $fileContent);
        }
    }



    protected function compileGlobalFunctions() : void
    {
        $globalFunctionFiles = \scandir("src/global_functions");
        $globalFunctionsSource = [];
        foreach ($globalFunctionFiles as $fileName) {
            if ($fileName !== "." && $fileName !== "..") {
                $tmpSource = \file_get_contents("src/global_functions/$fileName");
                $globalFunctionsSource[] = \substr($tmpSource, \strpos($tmpSource, "/**"));
            }
        }

        if (\count($globalFunctionsSource) > 0) {
            $source = "<?php\n\n class TempGlobalFunctions {\n\n\n".\implode("\n\n\n", $globalFunctionsSource)."}";
            \file_put_contents("src/TempGlobalFunctions.php", $source);
        }
    }


    protected function parseTempGlobalFunctions() : void
    {
        if (\file_exists("src/TempGlobalFunctions.php") === true) {
            $rstTempGlobalFunctions = \file_get_contents("docs/TempGlobalFunctions.rst");
            \unlink("src/TempGlobalFunctions.php");
            \unlink("docs/TempGlobalFunctions.rst");


            $rstTempGlobalFunctions = \substr(
                $rstTempGlobalFunctions,
                \strpos($rstTempGlobalFunctions, ".. rst-class:: public")
            );

            $rawRSTFunctions = \array_map(
                "trim",
                \explode(".. rst-class:: public", $rstTempGlobalFunctions)
            );


            if (\count($rawRSTFunctions) > 0) {
                if (\file_exists("docs/global_functions") === false) {
                    \mkdir("docs/global_functions");
                }
                else {
                    $this->clearDirectory("docs/global_functions");
                }

                foreach ($rawRSTFunctions as $rawRST) {
                    $rawLines = array_map("trim", explode("\n", $rawRST));
                    preg_match('/(\S+):: public (\S+)\(/', $rawLines[0], $output_array);

                    if (\count($output_array) === 3) {
                        $functionName = $output_array[2];
                        $lengthName = \mb_strlen($functionName);

                        $newRST = \str_repeat("=", $lengthName) . "\n";
                        $newRST .= $functionName . "\n";
                        $newRST .= \str_repeat("=", $lengthName) . "\n\n\n";
                        $newRST .= str_replace(".. php:method:: public", ".. php:function::", $rawRST);

                        \file_put_contents("docs/global_functions/$functionName.rst", $newRST);
                    }
                }
            }
        }
    }
}
