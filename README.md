 PHP-phpdoc-to-rst
===================


> Forked from Francesco "Abbadon1334" Danti by AeonDigital.  
> In this fork the changes has only visual and superficial effects [ just because I'm boring :) ].  
> Prefer to use the "Abbadon1334" version if you prefer a constantly updated project.

Efetua a extração da documentação técnica usando PHPDoc.


&nbsp;  
&nbsp;  


## Gerador de reStructuredText para Sphinx baseado em documentação obtida via PHPDoc
Este projeto é pesadamente baseado em [phpDocumentor/Reflection](https://github.com/phpDocumentor/Reflection)
e faz uso do [sphinxcontrib-phpdomain](https://github.com/markstory/sphinxcontrib-phpdomain).

**Dependências**
  - Python
  - Sphynx
  - Sphinx RTD Theme
  - Recommonmark

Segue abaixo um rápido tutorial sobre como instalar todas as dependências para o correto funcionamento deste projeto.


&nbsp;  
&nbsp;  


## Instalação do Python
Vá na [página oficial](https://www.python.org) e faça o download do executável mais recente e estável:


&nbsp;  
&nbsp;  


### Execute o instalador.

> Este tutorial foi feito para a versão 3.7.3 (windows).



1. Na primeira tela selecione a opção de adicionar o Python ao PATH do Windows.  
2. Selecione a instalação customizada.
   > Você pode remover a documentação.  
   > Mantenha o *pip*.  
   > Você pode remover a biblioteca Tkinter[^1].  
   > Mantenha o *Python Test Suite*.  
   > Pode remover também o *py launcher*[^2].  

3. Na próxima tela configure conforme sua preferencia e instale.
4. Após a instalação o programa oferece para desabilitar a limitação de 260 caracteres para a execução de comandos no Windows. Clique para desabilitar esta limitação e evitar problemas na execução.


[^1]: biblioteca para criação de ambiente gráfico; Não obrigatório para o uso do Sphinx.  
[^2]: serve para registrar "py" como evocador do python... como estamos definindo para adicionar o Python no PATH então isto não é necessário.


&nbsp;  
&nbsp;  


## Instalador do Sphinx
Abra um prompt de comando agora que o Python está instalado com o "pip".  
Faça a instalação do "Sphinx" usando o seguinte comando:
  > pip install -U sphinx

Confirme a instalação do mesmo com o comando:
  > sphinx-build --version


&nbsp;  
&nbsp;  


### Instalação do RTD Theme
[Saiba mais](https://pypi.org/project/sphinx-rtd-theme/) sobre o RTD Theme.
  > pip install sphinx_rtd_theme

&nbsp;  
&nbsp;  


### Instalação do analisador MarkDown
[Saiba Mais](http://www.sphinx-doc.org/en/master/usage/markdown.html) sobre o uso de MarkDown com o Sphynx.
  > pip install --upgrade recommonmark



&nbsp;  
&nbsp;  


## Usando o Sphynx
É aconselhado criar um diretório específico para a documentação do seu projeto. No caso dos projetos disponibilizados pela **Aeon Digital** será sempre usado o diretório `[./docs]` na raiz do projeto.  

Dentro deste diretório haverá um outro chamado `[./docs/source]` que é onde ficarão os arquivos fontes da documentação que serão manipulados pelo *Sphynx* para exportar os arquivos HTML ou de outro tipo que você deseje utilizar.  
Também dentro deste diretório estará um outro de nome `[./docs/source/_static]` que contém arquivos estáticos como imagens e folhas de estilo que serão utilizadas pelo Sphynx quando gerar a documentação final.
Outro diretório dentro de `[./docs]` será o `[./docs/html]` que estará apto a receber a documentação neste respectivo formato.  


&nbsp;  
&nbsp;  


### Documento "[./docs/source/conf.py]"
Neste documento devem ser adicionadas as configurações que o *Sphynx* usará para exportar a documentção existente dentro do diretório *source*.  
Para informações completas de como este arquivo funciona e quais suas principais configurações você pode conferir em http://www.sphinx-doc.org/en/master/config  


&nbsp;  
&nbsp;  


## Convertendo PHPDocs para RST
Nesta etapa o analizador provido por este próprio projeto irá se encarregar de ler todas as anotações PHPDocs contida nos arquivos que compõe o projeto alvo e então extraí-los para o formato reST.  

&nbsp;  

A partir do diretório raíz do projeto:  
Instale a biblioteca "phpdoc-to-rst" usando o comando:
> composer require --dev aeondigital/phpdoc-to-rst

Extraia o conteúdo usando o comando:
> ./vendor/bin/phpdoc-to-rst generate <output_directory> <source_directory>

- `output_directory`: Nome do diretório onde os documentos extraidos serão adicionados.
- `source_directory`: Nome do diretório onde estão os arquivos fonte para criação da documentação [normalmente será *src*].

**Exemplo**
> ./vendor/bin/phpdoc-to-rst generate docs/source src


## Gerando uma saída em um formato específico
Uma vez que você possui os arquivos reST contendo todo o conteúdo relativo as anotações PHPDocs do seu projeto, é hora de usar o Sphynx para converter esta massa de dados em outro formato como HTML ou Ebook.

&nbsp;  

A partir do diretório raíz do projeto:
> sphinx-build -b <output_type> <source_directory> <output_directory>

- `output_type`: Formato de saida da documentação [html, epub]
- `source_directory`: Nome do diretório onde estão os arquivos fonte para criação da documentação.
- `output_directory`: Nome do diretório onde os documentos extraidos serão adicionados.

**Exemplo**
> sphinx-build -b html docs/source docs/html  
> sphinx-build -b epub docs/source docs/epub 



&nbsp;  
&nbsp;  


## Importante
Como este projeto é um fork da versão que está sendo desenvolvida por [**Abbadon1334**](https://github.com/abbadon1334/phpdoc-to-rst) **apenas** com o propósito de ser voltada para detalhes visuais específicos é importante atentar para o fato que possivelmente esta peça de software vá estar com um atrazo quanto a implementação de correções ou novas features.  
