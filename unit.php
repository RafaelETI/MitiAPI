<?php
require_once('mod/Config.php'); new Config(true);

echo '<div>Miti Framework</div>';

//banco
$MitiBD=new MitiBD();

$MitiBD->requisitar('create table if not exists mitiunit(id tinyint(3) unsigned not null auto_increment,nome varchar(30) not null,primary key(id))');
$MitiBD->requisitar('insert into mitiunit(id,nome)values(1,"Filme")');
$MitiBD->requisitar('create table if not exists mitiunit2(id smallint(5) unsigned not null auto_increment,descricao varchar(1000) not null,categoria tinyint(3) unsigned not null,primary key(id),key categoria(categoria))');
$MitiBD->requisitar('insert into mitiunit2(id,descricao,categoria)values(90,"Gladiator (2000)",1),(91,"Spartacus (2004)",1),(92,"Ben Hur (1959)",1)');
$MitiBD->requisitar('alter table mitiunit2 add constraint memoria_ibfk_1 foreign key(categoria)references mitiunit(id) on update cascade');

//objetos
$MitiUnit=new MitiUnit();
$MitiAR=new MitiAR('mitiunit');
$MitiCRUD=new MitiCRUD(new MitiAR('mitiunit'));
$MitiData=new MitiData();
$MitiDesempenho=new MitiDesempenho();
//$MitiEmail=new MitiEmail();
$MitiPaginacao=new MitiPaginacao(10,2);
$MitiStatus=new MitiStatus();
$MitiTratamento=new MitiTratamento();
$MitiValidacao=new MitiValidacao();

//MitiAR
$MitiUnit->afirmar($MitiAR->getTabela(),'mitiunit','MitiAR::getTabela()');
$MitiUnit->afirmar($MitiAR->getTipos(),array('id'=>'float','nome'=>'string'),'MitiAR::getTipos()');
$MitiUnit->afirmar($MitiAR->getAnulaveis(),array('id'=>false,'nome'=>false),'MitiAR::getAnulaveis()');
$MitiUnit->afirmar($MitiAR->getTamanhos(),array('id'=>3,'nome'=>30),'MitiAR::getTamanhos()');
$MitiUnit->afirmar($MitiAR->getPkCampo(),'id','MitiAR::getPkCampo()');
$MitiUnit->afirmar($MitiAR->getPkTipo(),'float','MitiAR::getPkTipo()');

//MitiBD
$teste='\'"\\';
$MitiBD->escapar($teste);
$MitiUnit->afirmar($teste,'\\\'\\"\\\\','MitiBD::escapar(string)');

$teste=array("'",'"','\\');
$MitiBD->escapar($teste);
$MitiUnit->afirmar($teste,array("\\'",'\\"','\\\\'),'MitiBD::escapar(array)');

$MitiBD->requisitar('select id from mitiunit');

$MitiUnit->afirmar($MitiBD->getAfetados(),1,'MitiBD::getAfetados()');

$teste=$MitiBD->getId();
$MitiUnit->afirmar($teste,0,'MitiBD::getId()');

$teste=$MitiBD->obterAssoc();
$MitiUnit->afirmar($teste['id'],'1','MitiBD::requisitar()');

$MitiUnit->afirmar($MitiBD->obterQuantidade(),1,'MitiBD::obterQuantidade()');

$teste=$MitiBD->obterCampos();
$MitiUnit->afirmar($teste[0]->flags,49699,'MitiBD::obterCampos()');

//MitiCRUD
$id=$MitiCRUD->inserir(array('nome'=>'Teste'))->getId();
$MitiCRUD->definirCampos(array('nome'));
$teste=$MitiCRUD->ler(array('id'=>array('=',$id)))->obterAssoc();
$MitiUnit->afirmar($teste['nome'],'Teste','MitiCRUD::inserir()');

$MitiCRUD->alterar(array('nome'=>'Teste2'),$id);
$MitiCRUD->definirCampos(array('nome'));
$teste=$MitiCRUD->ler(array('id'=>array('=',$id)))->obterAssoc();
$MitiUnit->afirmar($teste['nome'],'Teste2','MitiCRUD::alterar()');

$MitiCRUD->definirCampos(array('nome'));
$MitiCRUD->ordenar(array('id'=>'desc'));
$teste=$MitiCRUD->ler()->obterAssoc();
$MitiUnit->afirmar($teste['nome'],'Teste2','MitiCRUD::ordenar()');

$MitiCRUD->inserir(array('nome'=>'Teste3'));
$MitiCRUD->inserir(array('nome'=>'Teste4'));
$MitiCRUD->inserir(array('nome'=>'Teste5'));
$MitiCRUD->limitar(3);
$MitiUnit->afirmar($MitiCRUD->ler()->obterQuantidade(),3,'MitiCRUD::limitar()');

$MitiCRUD->juntar(array('join'),array(new MitiAR('mitiunit2')),array('mitiunit'),array('id'),array('categoria'));
$MitiCRUD->definirCampos(array('id'),array(array('descricao')));
$teste=$MitiCRUD->ler()->obterAssoc();
$MitiUnit->afirmar($teste['mitiunit2_descricao'],'Ben Hur (1959)','MitiCRUD::juntar()');

$MitiCRUD->deletar($id);
$MitiCRUD->definirCampos(array('id'));
$MitiUnit->afirmar($MitiCRUD->ler(array('id'=>array('=',$id)))->obterQuantidade(),0,'MitiCRUD::deletar()');

//MitiData
$teste='18/08/1991';
$MitiData->br2Eua($teste);
$MitiUnit->afirmar($teste,'1991-08-18','MitiData::br2Eua()');

$teste='1991-08-18';
$MitiData->eua2Br($teste);
$MitiUnit->afirmar($teste,'18/08/1991','MitiData::eua2Br()');

$teste='1991-08-18';
$MitiUnit->afirmar($MitiData->obterDiaSemana($teste),'Dom','MitiData::obterDiaSemana()');

$teste='08';
$MitiData->obterMes($teste);
$MitiUnit->afirmar($teste,'Agosto','MitiData::obterMes()');

//MitiDesempenho
$teste=array(1391905903.114,1391905984.1241);
$MitiUnit->afirmar($MitiDesempenho->medirTempoExecucao($teste),'81.010','MitiDesempenho::medirTempoExecucao()');

//MitiPaginacao
$MitiPaginacao->setTotal(100);
$teste='<a href="?pg=1">Primeira</a><a href="?pg=1">Anterior</a><a href="?pg=1">1</a><span class="on">2</span><a href="?pg=3">3</a><a href="?pg=3">Próxima</a><a href="?pg=10">Última</a>';
$MitiUnit->afirmar($MitiPaginacao->criar('?pg=',3,'off','on'),$teste,'MitiPaginacao::criar()');

//MitiStatus
$_SESSION['status']=true;
$MitiUnit->afirmar($MitiStatus->obterMensagem(),'O procedimento foi realizado com sucesso','MitiStatus::obterMensagem()');
unset($_SESSION['status']);

$afirmacao='<script>alert("teste"); location.href="teste.php";</script>';
$MitiUnit->afirmar($MitiStatus->obterAlerta('teste','teste.php'),$afirmacao,'MitiStatus::obterAlerta()');

//MitiTratamento
$teste='\'"&<>';
$MitiTratamento->htmlSpecialChars($teste);
$MitiUnit->afirmar($teste,'&#039;&quot;&amp;&lt;&gt;','MitiTratamento::htmlSpecialChars(string)');

$teste=array("'",'"','&','<','>');
$MitiTratamento->htmlSpecialChars($teste);
$MitiUnit->afirmar($teste,array('&#039;','&quot;','&amp;','&lt;','&gt;'),'MitiTratamento::htmlSpecialChars(array)');

$teste='aaaaaaaaaa';
$MitiTratamento->encurtar($teste);
$MitiUnit->afirmar($teste,'aaaaa...','MitiTratamento::encurtar(string)');

$teste=array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc');
$MitiTratamento->encurtar($teste);
$MitiUnit->afirmar($teste,array('aaaaa...','bbbbb...','ccccc...'),'MitiTratamento::encurtar(array)');

$teste='ç';
$MitiTratamento->removerAcentos($teste);
$MitiUnit->afirmar($teste,'c','MitiTratamento::removerAcentos(string)');

$teste=array('á','È','î','Õ','ü','Ç');
$MitiTratamento->removerAcentos($teste);
$MitiUnit->afirmar($teste,array('a','E','i','O','u','C'),'MitiTratamento::removerAcentos(array)');

//MitiValidacao
$teste='teste';
$MitiValidacao->tamanho($teste,5);
$MitiUnit->afirmar($teste,$teste,'MitiValidacao::tamanho()');

$teste='conta@dominio.com';
$MitiValidacao->email($teste);
$MitiUnit->afirmar($teste,$teste,'MitiValidacao::email()');

$teste='a';
$MitiValidacao->vazio($teste);
$MitiUnit->afirmar($teste,$teste,'MitiValidacao::vazio(string)');

$teste=array('a','b','c');
$MitiValidacao->vazio($teste);
$MitiUnit->afirmar($teste,$teste,'MitiValidacao::vazio(array)');

$_FILES['arquivo']['name'][0]='mitiunit.png';
$_FILES['arquivo']['type'][0]='image/png';
$_FILES['arquivo']['tmp_name'][0]=RAIZ.'img/mitiunit.png';
$_FILES['arquivo']['size'][0]='1457';
$MitiValidacao->upload('arquivo',array('jpeg','png','gif'),2048,true,16,16);
$MitiUnit->afirmar($_FILES['arquivo']['name'],$_FILES['arquivo']['name'],'MitiValidacao::upload()');

$teste='11550994603';
$MitiValidacao->cpf($teste);
$MitiUnit->afirmar($teste,$teste,'MitiValidacao::cpf()');

$teste='87210343000169';
$MitiValidacao->cnpj($teste);
$MitiUnit->afirmar($teste,$teste,'MitiValidacao::cnpj()');

//banco
$MitiBD->requisitar('drop table mitiunit2');
$MitiBD->requisitar('drop table mitiunit');

$MitiBD->fechar();
?>
