# Arquivo de configuração para a criação de documentação com o Sphinx.
#
# Para informações completas de como este arquivo funciona e quais suas 
# principais configurações veja mais em:
# http://www.sphinx-doc.org/en/master/config
import sphinx_rtd_theme




# -- Informações do Projeto --------------------------------------------------

project = '[[PROJECT-NAME]]'
copyright = '[[COPYRIGHT-YEAR]], [[COPYRIGHT-NAME]]'
author = '[[AUTHOR-NAME]]'

# Versão atual
release = '[[PROJECT-VERSION]]'





# -- Configurações Gerais ----------------------------------------------------

# Nome do documento principal, que contem as diretivas "tocTree".
master_doc = 'index'



# Permite definir um trecho de marcação "reStructuredText" a ser usado para 
# todos os demais arquivos que serão processados.
# Strings literais serão adicionadas no INICIO de cada um dos documentos
rst_epilog = """
.. |br| raw:: html

   <br />

"""



# Permite definir um trecho de marcação "reStructuredText" a ser usado para 
# todos os demais arquivos que serão processados.
# Strings literais serão adicionadas no FINAL de cada um dos documentos
rst_prolog = ''



# Lista de padrões para identificação de arquivos e diretórios (relativos 
# a este próprio diretório) que devem ser ignorados no processamento da 
# documentação.
# 
# Esta configuração afeta as seguintes:
# "html_static_path" e "html_extra_path".
exclude_patterns = []



# Adicione qualquer módulo de extenção do Sphinx aqui em formato de strings.
#
# 'recommonmark' é uma extenção que permite o uso de documentos MarkDown.
# 'sphinxcontrib.phpdomain' é uma extenção que marca corretamente blocos
# de código PHP.
extensions = ['recommonmark', 'sphinxcontrib.phpdomain']



# Registra as extenções dos arquivos que devem ser analisados conforme cada
# analisador correspondente.
source_suffix = {
    '.rst': 'restructuredtext',
    '.md': 'markdown',
}



# Adicione aqui os caminhos (relativos a este próprio diretório) até os 
# templates que serão utilizados.
templates_path = ['_templates']





# -- Linguagens --------------------------------------------------------------

# Linguagem usada pelo conteúdo autogerado pelo Sphinx. 
# 
# Isto é utilizado se você usar traduções via catalogos "gettext".
language = 'pt-BR'



# Diretórios onde devem estar as definições específicas para cada lingua 
# contemplada pela documentação.
locale_dirs = ['locales']





# -- Opções para a saida HTML ------------------------------------------------

# Definição do Theme que será usado para a construção da documentação no 
# formato HTML.  
# Veja a documentação completa em:
# http://www.sphinx-doc.org/en/master/usage/configuration.html
# https://pypi.org/project/sphinx-rtd-theme/
html_theme = 'sphinx_rtd_theme'



# Configurações específicas para o Theme selecionado.
# No caso de um Theme diferente de "sphinx_rtd_theme", conheça as demais
# configurações em 
# http://www.sphinx-doc.org/en/master/usage/theming.html#builtin-themes
html_theme_options = {
    #'analytics_id': 'UA-XXXXXXX-1', 
    'canonical_url': '',    
    'logo_only': True,
    'display_version': True,
    'prev_next_buttons_location': 'bottom',
    'style_external_links': False,
    'style_nav_header_background': '#175E8D',

    'collapse_navigation': True,
    'sticky_navigation': True,
    'navigation_depth': 4,
    'includehidden': True,
    'titles_only': False
}



# Título para ser usado em cada página que será gerada.
# Pode ser usado uma "string" ou um esquema utilizando o nome das
# variáveis descritas neste próprio documento.
html_title = project + ' - ' + release



# Imagem logotipo a ser usado pela documentação.
# Ele será usado no topo da barra lateral e seu tamanho não deve
# exceder aos 200px.
html_logo = '_static/ico_aeon_digital.png'



# Icone do tipo "favicon" que será incorporado nos documentos finais.
html_favicon = '_static/favicon.ico'



# Lista de caminhos (relativos a este próprio diretório) onde estarão 
# alocados arquivos customizados (como folhas de estilo ou javascript).
#
# Estes arquivos serão copiados para o diretório onde a documentação será
# exportada.
html_static_path = ['_static']



# Permite definir um ou mais arquivos de estilo a serem adicionados
# para os documentos que serão exportados.
def setup(app):
    app.add_stylesheet('styles.css')



# Indica se os arquivos fontes das páginas da documentação devem ser copiados
# para o diretório "_sources" da documentação exportada.
html_copy_source = False



# Indica se deve adicionar um link para o código fonte da respectiva página.
# Apenas funciona se "html_copy_source" for True.
html_show_sourcelink = False



# Indica se deve adicionar a marcação de CopyRight.
html_show_copyright = True



# Indica se deve mostrar a marcação "Created using Sphinx".
html_show_sphinx = False



# Define o encoding padrão para os arquivos gerados.
html_output_encoding = 'utf-8'



# Lingua a ser usada para gerar o índice de pesquisa.
# O padrão é a mesma lingua definida em "language"
html_search_language = 'pt'





# -- Opções para a saida EPUB ------------------------------------------------

# Nome para o documento.
# O comum é o próprio nome do projeto.
epub_basename = 'AeonDigital -- ' + project + ' - ' + release



# Descrição do documento
epub_description = 'Funções e Constantes que extendem a coleção original do core PHP.'



# Autor do documento.
epub_author = 'Rianna Cantarelli'



# Nome das pessoas e/ou empresas que contribuiram para a formação deste documento.
epub_contributor = ''



# Lingua do documento.
epub_language = 'pt-BR'



# Nome da instituição/empresa/pessoa responsável pela publicação deste documento.
epub_publisher = 'AeonDigital'



# CopyRight do documento
epub_copyright = 'AeonDigital'



# Indica como as URLs devem ser mostradas
epub_show_urls = 'footnote'



# Capa para o documento.
# epub_cover = ('_static/epub-cover.jpg', 'epub-cover.xhtml')



# Definição do Theme que será usado para a construção da documentação no 
# formato EPUB.  
# Veja a documentação completa em:
# http://www.sphinx-doc.org/en/master/usage/configuration.html
# https://pypi.org/project/sphinx-rtd-theme/
# epub_theme = 'sphinx_rtd_theme'



# Configurações específicas para o Theme selecionado.
# No caso de um Theme diferente de "sphinx_rtd_theme", conheça as demais
# configurações em 
# http://www.sphinx-doc.org/en/master/usage/theming.html#builtin-themes
# epub_theme_options = {
#    'logo_only': True,
#    'display_version': True,
#    'prev_next_buttons_location': 'bottom',
#    'style_external_links': False,
#    'style_nav_header_background': '#175E8D',

#    'collapse_navigation': True,
#    'sticky_navigation': True,
#    'navigation_depth': 4,
#    'includehidden': True,
#    'titles_only': False
#}
