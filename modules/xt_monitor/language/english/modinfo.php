<?php
if ( !defined("_XT_MONITOR_INCLUDED") ) {
	define("_XT_MONITOR_INCLUDED",1);
	if ( !defined("_XT_MONITOR_INC") ) {
		define("_XT_MONITOR_INC",1);
	}	

define("_MI_XT_MONITOR_DIRNAME","xt_monitor");  // definir igual ao $sys_prefix  do config.php
define("_MI_XT_MONITOR_NAME","XT Monitor ");

define('_MI_XT_MONITOR_HELP','help');
define('_MI_XT_MONITOR_CONSULTA','Consultation');
define('_MI_XT_MONITOR_CONFMOD','Configuring modules');
define('_MI_XT_MONITOR_LIMPREG','Clear Records');
define('_MI_XT_MONITOR_VERERR','See Errors');
define('_MI_XT_MONITOR_CONTARQ','Control Files');
define('_MI_XT_MONITOR_PARAM','Parameters');
define('_MI_XT_MONITOR_GERAHASH','Generating hashes');
define('_MI_XT_MONITOR_EXECVERIF','Run Verification');

//monitora
define('XT_MONITOR_NOTTABLE','There are no tables to track');

define('XT_MONITOR_MODULONOT','Module does not need to monitor: ');
define('XT_MONITOR_TABLENOT','Table does not need to be monitored :');

define('XT_MONITOR_ALTEROU',' change of: ');
define('XT_MONITOR_PARA',' to: ');
define('XT_MONITOR_ERRSQL',' Error in SQL ');

define('XT_MONITOR_SQLGRV',' Sql recorded - module: ');
define('XT_MONITOR_TABLE',' table: ');

// Main
define("_MD_XT_MONITOR_NAME",'XT Monitor');
define("_MI_XT_MONITOR_C_DIRNAME",'xt_monitor');  

// Admin
define('_AM_XT_MONITOR_PARAM_NOT_CAD','Parameters not registered, go to Settings tab, to define them');

define('_AM_XT_MONITOR_NOTCONF','XT_MONITOR Is not configured properly, look at Help: How to Turn ');


define('_AM_XT_MONITOR_TEMACONSULTA','Query logs of hits - Report to filter data ');

define('_AM_XT_MONITOR_SELCAMPOS','Select the fields you want');

define('_AM_XT_MONITOR_MARCALL','Mark/Unmark All');

define('_AM_XT_MONITOR_LOCSAI','Local Output');
define('_AM_XT_MONITOR_TELA','Screen');
define('_AM_XT_MONITOR_PDF','Printer (pdf)');
define('_AM_XT_MONITOR_CSV','File (csv) ');
define('_AM_XT_MONITOR_XLS','File Excell (xls) ');
define('_AM_XT_MONITOR_ORIENT','Outline of Film: (pdf)');

define('_AM_XT_MONITOR_VERT','Vertical ');
define('_AM_XT_MONITOR_HORIZ','Horizontal ');
define('_AM_XT_MONITOR_FORMPAPER','Select the paper size (pdf)');

define('_AM_XT_MONITOR_REGPPAGE','Records per page:');

define('_AM_XT_MONITOR_PERIODO','Period');
define('_AM_XT_MONITOR_INICIO','Home');
define('_AM_XT_MONITOR_FIM','End');
define('_AM_XT_MONITOR_ATE',' until ');

define('_AM_XT_MONITOR_CONTROLEARQ_HELP','
<H3  align="center" >CONTROLE DE ARQUIVOS - Monitora alterações de arquivos no Servidor  </H3> 
<div style="width:50%;margin-left:25%" >
<ol>
<li><a href="#a)"> Objetivo </a><br></li>
<li><a href="#b)"> Como Funciona  </a> <br></li>
<li><a href="#c)"> Como Proceder </a><br></li>
<li><a href="#d)"> Configuração do Apache  </a><br></li>
<li><a href="#e)"> Como Executar Verificação  </a><br></li>
<li><a href="#f)"> Recomendação </a><br></li>
</ol>

</div>

<h4><a name="a)"> Objetivo </a></h4>
Monitorar arquivos em um site de produção, permitindo perceber quando , por exemplo, o site for invadido .<br>
Se alguem conseguir invadir , enviando algum arquivo, ou mesmo alterando algum ja existente, o administrador será notificado.<br>
<b>Configurações possíveis: </b>
<ol>
<li>Configurar a partir de qual pasta, deseja monitorar.</li>
<li>Definir quais pastas não precisam ser monitoradas. </li>
<li>Definir mais de um email para receber notificação sobre o monitoramento.</li>
<li>Denifir quais extensões de arquivos deseja monitorar, como  .php, .inc , etc.</li>
<li>Gerar ou não arquivos tipo (.htaccess) do Apache, bloqueando execução dos arquivos suspeitos (alterados ou novos)   </li>
<li>Definir o nome do arquivo tipo (.htaccess) do Apache, que deve ser gerado. <br>
&nbsp;&nbsp;&nbsp;&nbsp;(Obs. Isso depende da configuração do Apache)
</li>
</ol>
<h4><a name="b)"> Como Funciona  </a></h4>
Primeiro gera um hashe  dos arquivos desde a pasta configurada em parâmetros  .<br>
Periodicamente, faz verificação notificando os arquivos alterados, removidos e novos, para emails configurados em parâmetros.<br>
Poderá gerar dinâmicamente arquivos do Servidor Web Apache,<b>.htaccess </b> para impedir o acessos aos arquivos inclusos e alterados.<br>
Até que o administrador, possa tomar providências, eliminando os arquivos, ou gerando novos hashes dos arquivos, caso sejam confiáveis.

<h4><a name="c)">Como Proceder </a> </h4>

Após instalação do módulo, deve-se definir os Parâmetros, na respectiva Aba.<br>
Então, deve-se "Gerar Hashes"  , na  respectiva Aba <br>
Sempre que houver uma atualização , nos arquivos das pastas monitoradas , 
como instalação/atualização de um módulo , deverá "Gerar Hashes"  novamente.<br>
Agora, próximo passo, é "Executar Verificação"  periodicamente.<br>

<h4><a name="d)">Configuração do Apache </a></h4>
Se optou por gerar arquivos do tipo (.htaccess) do Apache, deve-se observar as configurações do Apache.<br>
Antes de mais nada, você precisará se certificar que o Apache está configurado para aceitar os arquivos .htaccess como 
arquivos especiais. <br>
Para configurar, você precisará editar o arquivo de configuração do Apache, que é o "httpd.conf".<br>
Geralmente ele está localizado no diretório "/etc/httpd/conf". <br>
<br>
A linha abaixo deve estar descomentada e pode-se trocar .htaccess pelo nome que desejar, como exemplo .conf    <br>
<br> Note que o nome que informar aqui , deve ser o mesmo que informar nos parâmetros do módulo . <br>
<b>AccessFileName .htaccess</b>
<br>
Ainda dentro deste arquivo, você encontrará uma ou duas diretrizes mais ou menos desta forma:<br>
<br>
&lt;Directory /&gt;<br>
    Options FollowSymLinks<br>
    <b>AllowOverride None</b><br>
&lt;/Directory	&gt;<br>
<br>
ou <br>
<br>
&lt;Directory "/var/www/html"  	&gt; <br>
    Options Indexes FollowSymLinks<br>
    <b>AllowOverride None</b><br>
    Order allow,deny<br>
    Allow from all<br>
&lt;/Directory 	&gt;<br>
<br>
<br>
Localize tags de configuração do diretório raiz do site<br>
Alterar tag  <b>AllowOverride None</b>  para <b>AllowOverride All</b> <br>
<br>

Considerando que está usando .htaccess, o container FilesMatch deve estar configurado como abaixo,
para negar acesso à esses arquivos, via browser.<br>
<br>
&lt;FilesMatch "^\.ht" &gt;<br>
    Order allow,deny<br>
    Deny from all<br>
&lt;/FilesMatch &gt;<br>
<br>

<h4><a name="e)"> Como Executar Verificação </a> </h4>
<b>Manualmente: </b><br>
Na área administrativa , na aba de mesmo nome.<br><br>

<b>Agendar em estação Windows: </b><br>
Para facilitar , crie um pasta de trabalho, por exemplo: c:\xt_monitor-agenda<br>
<br>
Usando um editor de texto como por exemplo o Bloco de notas , crie um arquivo chamado xt_monitor-automatico.bat dentro da pasta criada.<br>
Conteúdo do xt_monitor-automatico.bat :<br>
C:<br>
cd \xt_monitor-agenda <br>
xt_monitor-automatico.url <br>
<br>
Vamos criar o arquivo chamado xt_monitor-automatico.url :<br>
Na área de trabalho, clique com o botão direito do mouse - novo - atalho<br>
Na linha de comando informe: '.XOOPS_URL.'/modules/xt_monitor/includes/xt_monitor_agenda.php .<br>
Clique em Avançar e no nome do atalho informe xt_monitor-automatico .<br>
Neste momento foi criado o arquivo xt_monitor-automatico.url<br>
<br>
Copie o arquivo xt_monitor-automatico da área de trabalho para a pasta c:\xt_monitor-agenda<br>
<br>
Agora vá no Painel de controle , procure o ícone Tarefas Agendadas - Adicionar Tarefa Agendada<br>
Localize o arquivo xt_monitor-automatico.bat e defina os horários que desejar.<br>
<br>
<br>
<b>Agendar em Servidor Linux  ou Windows : </b><i>(Recomendado quando deseja gerar arquivo tipo (.htaccess)</i> <br><br>
Configure o crontab ou Agendador de Tarefas ,nos horários desejados para executar verificação.<br>
Certifique-se que o PHP pode ser executado em linha de comando e que está no 
PATH ou informe o caminho completo do binário do php, na linha de comando .<br>
Coloque a instrução abaixo na linha de comando:<br>
php -f '.XOOPS_ROOT_PATH.'/modules/xt_monitor/includes/xt_monitor_agenda.php
<h4><a name="f)">Recomendação </a> </h4>
Recomendamos que as pastas do xoops que devem ter permissão de escrita, como 
templates_c, cache e upload, tenham constantemente arquivo tipo .htaccess com 
o conteúdo:<br>
 <b>Deny from all </b><br>
 Dessa forma, não será possível executar um arquivo diretamente pela url, 
 mas ele poderá ser usado pelos outros scripts, não afetando o funcionamento.<br>
 E essas pastas, devem ser configuradas nos parâmetros do módulo , para não serem verificadas,
 pois lá são  gerados arquivos dinâmicamente como templates do smarty e seriam detectados pelo
 xt_monitor como arquivos suspeitos, gerando notificações.<br>
 

');

define("_AM_XT_MONITOR_HELP",
'<H3  align="center" >XT_MONITOR - Monitora atualizações no Banco de Dados  </H3> 
<div style="width:90%;margin-left:25%" >
<ol>
<li><a href="#a)"> Objetivo </a><br></li>
<li><a href="#b)"> Por que monitorar tabelas ?  </a> <br></li>
<li><a href="#h)"> Como Consultar monitoramento   </a> <br></li>
<li><a href="#c)"> Como Ativar </a><br></li>
<li><a href="#d)"> Erros no módulo   </a><br></li>
<li><a href="#e)"> Como outros módulos poderão passar informações extras ?  </a><br></li>
<li><a href="#f)"> Debug  </a><br></li>
<li><a href="#g)"> Atenção para   </a><br></li>
<li><a href="xt_monitor_controle_arq_help.php?xmenu=5"> Controle de Arquivos   </a><br></li>
</ol>

</div>
<h4><a name="a)"> Objetivo </a></h4>
Registra alterações no Banco de Dados, através do objeto  $xoopsDB, logo se algum módulo não usar este objeto para atualizar o Banco, não será monitorado.  <br>
É possível configurar os módulos e quais tabelas deseja monitorar, na aba "Configurar Módulos"  .<br>
Para otimizar, a informação sobre módulos e tabelas a serem monitorados , são guardados na sessão. <br>
Portanto, se fizer alguma alteração em  "Configurar Módulos", so terá efeito após o próximo login.<br>
Serão monitorados os comandos:  INSERT,  UPDATE e DELETE. <br>

<h4><a name="b)">Por que monitorar tabelas ? </a></h4>
Digamos que você tenha um módulo , no qual  precise saber quem anda alterando determinada tabela, cujo teor da informação seja muito importante e estratégico.<br>
Com o xt_monitor, você pode fazer isso, sem alterar nada em seus módulos específicos. Bastando apenas que ele seja um módulo do xoops .

<h4><a name="h)">Como Consultar monitoramento </a></h4>
Na aba "Consulta", é possível definir o período, filtros para busca, definir os campos que deseja ver no relatório.<br>
É possível definir também a saída : html, pdf, arquivo (csv) ou planilha do excell.<br>
É possível também exibir somente as querys que retornaram erro na execucão. <br>


<h4><a name="c)">Como Ativar </a> </h4>
Se estiver usando a versão XOOPS do XT (<a href="http://www.xoopstotal.com.br"> Xoops Total </a>),após instalar ja estará ativado, através do esquema de plugins .<br>
Caso contrário, após instalar o módulo, será necessário incluir uma linha em um arquivo do Kernel do xoops.<br>
Arquivo : <raiz do xoops>/class/logger.php ,  no final da function addQuery(...), inserir<br>
<b>include(XOOPS_ROOT_PATH."/modules/xt_monitor/xt_monitor_monitora.php"); </b>	

<h4><a name="d)">Erros no módulo </a> </h4>
Na aba "Ver Erros", será registrado erros que possam ocorrer , no processo do monitoramento.     <br>
Se o administrador, não conseguir resolvê-los, poderá entrar em contato com o desenvolvedor do módulo: <br>
(<a href="mailto:claudia.avcallegari@gmail.com"><i> Claudia A. V. Callegari </i></a> ) </i><br>
Ainda nessa aba, é possível limpar o arquivo.<br>

<h4><a name="e)">Como outros módulos poderão passar informações extras ? </a></h4>
O desenvolvedor que desejar aproveitar o registro para atualizar o campo de observação, deverá colocar a informação em
$GLOBALS["xt_monitor_obs"]=" campo alterou de  para ";<br>
Por exemplo, digamos que queira registrar os campos que foram alterados, mostrando o antes e o depois .<br>
Mas ha uma forma automática de registrar alterações em campos do tipo mudou de para.<br>
Guardar o objeto antes de alterar nesta variável :$GLOBALS["xt_monitor_objantes"]= "objeto_antes_de_alterar_atributos";<br>
Apos atualizar os atributos no objeto, guarda-lo em $GLOBALS["xt_monitor_objdepois"]="objeto_apos_atualizar_atributos"; <br>
Dessa forma, quando o xt_monitor for registrar a sql, irá verificar se esses 2 objetos são da mesma classe e comparar
 para registrar os atributos que foram alterados.
	

<h4><a name="f)">Debug </a></h4>
Quando o debug do xoops , tipo "Exibir erros do MySQL/Blocos", estiver ativado, será possível ver
debug  do  xt_monitor  na  parte  EXTRA, mostrando se gravou a sql ou se o módulo/tabela não precisa ser monitorado.

<h4> <a name="g)">Atenção para  </a> </h4>
Quando não informar as tabelas que deseja monitorar, todas as tabelas serão monitoradas, na chamda do módulo em questão.<br>
Porém, isso inclui qualquer tabela atualizada no carregamento do módulo, por exemplo SESSION, etc.<br>
');

// p/ parâmetros 
define('_AM_XT_MONITOR_EXT','Extensões de arquivos válidas<br>Separar com ;');
define('_AM_XT_MONITOR_PASTASOFF','Pastas que não precisam de verificação<br>Separar com ;');
define('_AM_XT_MONITOR_EMAILS','Emails para enviar avisos<br>Separar com ;');
define('_AM_XT_MONITOR_DIRPATH','Pasta para iniciar a verificação<br>Se for pasta raiz do xoops, deixar vazio');

define('_AM_XT_MONITOR_PASTAUPLOAD','"Atenção !!! Pasta para gravação de arquivos de erros,<br>caso necessário,não permite escrita');

define('_AM_XT_MONITOR_GERARQ','Deseja gerar arquivos tipo (.htaccess) para Apache, restringindo acesso à arquivos novos/alterados?
<br>O Apache deve estar configurado para ler esses arquivos (veja no Help) ');

define('_AM_XT_MONITOR_NOMEARQ','Nome do arquivo para gerar. Default: (.htaccess)<br>
Se for diferente do default, o Apache deve estar configurado.  ');

}





