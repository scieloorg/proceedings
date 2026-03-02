export PATH=$PATH:.
rem Este arquivo é uma chamada para o 
rem GeraSciELO.bat com parâmetros STANDARD

clear
echo === ATENCAO ===
echo 
echo Este arquivo executara o seguinte comando
echo GeraSciELO.bat .. /scielo/web log/GeraPadrao.log adiciona
echo 
echo Tecle CONTROL-C para sair ou ENTER para continuar...

rem read pause

GeraSciELO.bat .. .. log/GeraPadrao.log adiciona
