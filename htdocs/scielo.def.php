[SITE_INFO]
SITE_NAME=SciELO Proceedings
SHORT_NAME=SciELO Proceedings
#SITE_AUTHOR=FAPESP - BIREME
SITE_AUTHOR=FAPESP - CAPES - CNPq - BIREME - FapUNIFESP
#ADDRESS_1=Rua Machado Bittencourt, 430 - Vila Clementino
ADDRESS_1=Avenida Onze de Junho, 269 - Vila Clementino
#ADDRESS_2=04044-001 São Paulo SP
ADDRESS_2=04041-050 São Paulo SP
COUNTRY_1=Brazil^len
COUNTRY_2=Brasil^lpt
COUNTRY_3=Brasil^les
#PHONE_NUMBER=(55 11) 5083-3639/59
PHONE_NUMBER=+55 11 5083-3639/59
E_MAIL=scielo@scielo.org
STANDARD_LANG=en
APP_NAME=proceedings
DBTITLE_EN=Events
DBTITLE_ES=Eventos
DBTITLE_PT=Eventos
DBARTICLE_EN=Papers
DBARTICLE_ES=Trabajos
DBARTICLE_PT=Trabalhos

[LILACS]
SERVER_LILACS=www.bireme.br
PATH_WXIS_LILACS=/cgi-bin/wxislind.exe
PATH_CGI_BIN_IAH_LILACS=iah/
PATH_DATA_IAH_LILACS=/iah/online/

[MEDLINE]
SERVER_MEDLINE=www.bireme.br
PATH_WXIS_MEDLINE=/cgi-bin/wxislind.exe
PATH_CGI_BIN_IAH_MEDLINE=iah/
PATH_DATA_IAH_MEDLINE=/iah/online/

[SCIELO Proceedings]
SERVER_SCIELO=scielo-proceedings
PATH_WXIS_SCIELO=/cgi-bin/wxis.exe
PATH_CGI_BIN_IAH_SCIELO=iah/
PATH_DATA_IAH_SCIELO=/iah/

[ADOLEC]
SERVER_ADOLEC=www.bireme.br
PATH_WXIS_ADOLEC=/cgi-bin/wxislind.exe
PATH_CGI_BIN_IAH_ADOLEC=iah/
PATH_DATA_IAH_ADOLEC=/iah/online/

[PATH]
PATH_DATA=/
PATH_CGI-BIN=/cgi-bin/
PATH_SCRIPTS=ScieloXML/
PATH_GENIMG=/img/
PATH_SERIMG=/img/eventos/
PATH_SERIAL_HTML=/eventos/
PATH_XSL=/var/www/html/htdocs/xsl/
PATH_DATABASE=/var/www/html/bases/
PATH_SETTINGS=
PATH_PDF=/var/www/html/bases/pdf/
PATH_TRANSLATION=/var/www/html/bases/translation/
PATH_HTDOCS=/var/www/html/htdocs/

[LOG]
ACTIVATE_LOG=1
ENABLE_STATISTICS_LINK=0
ENABLE_CITATION_REPORTS_LINK=0
SERVER_LOG=scielo-log.bireme.br
SCRIPT_LOG_NAME=scielolog/updateLog02.php
SCRIPT_LOG_RUN="scielolog/scielolog03B2.php?app=proceedings"
MYSQL_USER=logserver
MYSQL_PASSWORD=20log01
ACTIVATE_GOOGLE=0
GOOGLE_CODE=UA-604844-1
PATH_LOG_CACHE=/var/www/html/bases/pages/sci_stat/
SCRIPT_TOP_TEN="http://scielo-log.bireme.br/scielolog/ofigraph20.php?app=proceedings"
SCRIPT_ARTICLES_PER_MONTH="http://scielo-log.bireme.br/scielolog/ofigraph21.php?app=proceedings"

[LOG_DATABASE_INFO]
DB_NAME = 
TABLE_NAME = 
TABLE_JOURNALS_NAME = 
TABLE_ISSUES_NAME = 
TABLE_ARTICLES_NAME = 
ADMIN_EMAIL = 


[CACHE]
ENABLED_CACHE=0
SERVER_CACHE=localhost
SERVER_PORT_CACHE=11211
MAX_DAYS = 1
MAX_SIZE = 9368709120 
PATH_CACHE=/var/www/html/bases/pages/
CHECK_EXPIRED = 0
CACHE_STATUS = off

[SOCKET]
SOCK_PORT=9007
ACCESS_LOG_FILE=/var/www/html/bases/logs/socket_access_log.log
ENABLE_ACCESS_LOG=0

[SCIELO_REGIONAL]
SCIELO_REGIONAL_DOMAIN=teste.scielo.org
login_url=/applications/scielo-org/sso/loginScielo.php
logout_url=/applications/scielo-org/sso/logoutScielo.php
check_login_url=/applications/scielo-org/sso/checkLogin.php

[services]
sci_artlangs="http://teste.trigramas.bvs.br/cgi-bin/wxis.exe/?IsisScript=ScieloXML/sci_artlangs.xis&def=scielo.def&pid=PARAM_PID"
show_toolbox=1
show_requests=0
show_login=0
show_datasus=0
show_send_by_email=0
show_cited_scielo=0
show_cited_google=0
show_similar_in_scielo=0
show_similar_in_google=0
_google_last_process=20060901
show_article_references=0


[FULLTEXT_SERVICES]
access="http://128.0.0.1:8081/applications/scielo-org/pages/services/articleRequestGraphicPage.php?pid=PARAM_PID&caller=PARAM_SERVER"
cited_SciELO="http://128.0.0.1:8081/scieloOrg/php/citedScielo.php?pid=PARAM_PID"
cited_SciELO_service="http://trigramas.bireme.br/cgi-bin/mxlind/cgi=@cited?pid=PARAM_PID"
similar_SciELO_service="http://trigramas.bireme.br/cgi-bin/mx/cgi=@1?xml&collection=SciELO.org.TiKwAb&minsim=0.30&maxrel=30&show=scielo1&text=PARAM_TEXT"
cited_Google="http://scholar.google.com/scholar?q=link:CURRENT_URL"
related_Google="http://scholar.google.com/scholar?q=related:CURRENT_URL"
related="http://128.0.0.1:8081/scieloOrg/php/related.php?pid=PARAM_PID"
related_service="http://trigramas.bireme.br/cgi-bin/mxlind/cgi=@related?pid=PARAM_PID"
send_mail="http://128.0.0.1:8081/applications/scielo-org/pages/services/sendMail.php?pid=PARAM_PID&caller=PARAM_SERVER"

[mimetex]
mimetex=/cgi-bin/mimetex.cgi

[language]
client_charset = utf-8

[XML_ERROR]
ENABLED_XML_ERROR=0
ENABLED_MAIL_ALERT=0
ENABLED_LOG_XML_ERROR=1
MAILTO_XML_ERROR=xxx@xxx.xxx
NAMETO_XML_ERROR=SciELO
LOG_XML_ERROR_FILENAME=/var/www/html/logs/xml_error_log.txt

[DIVULGACAO]
ENABLE_DIVULGACAO=0

[PRESENTATION]
BEFOREPRINT_ISSUES=splited
BEFOREPRINT_ISSUETOC=splited
xBEFOREPRINT_ISSUES=together
xBEFOREPRINT_ISSUETOC=together
