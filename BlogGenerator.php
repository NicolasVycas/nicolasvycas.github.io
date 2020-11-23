<?php
include('./DataBaseConnector.php');
require('./vendor/erusev/parsedown/Parsedown.php');
$GLOBALS['PostsDir'] = './Posts';
$GLOBALS['PorjetosDir'] = './Projetos';
$GLOBALS['TemplateDir'] = './Templates';
function Limpar($dir){
    $posts = scandir($dir);
    unset($posts[0]);
    unset($posts[1]);
    foreach($posts as $fille){
        unlink($dir.'/'.$fille);
    }
}
function EscreverArquivo($dir,$post,$TempleteFile){
    $Parsedown = new Parsedown();
    $PostHtml = fopen($dir.'/'.$post['Titulo'].'.html','x');
    //Gera os conteudos
    $ConteudoTotal = file_get_contents($TempleteFile);
    $ConteudoTotal = str_replace('{{Titulo}}',$post['Titulo'],$ConteudoTotal); 
    $ConteudoTotal = str_replace('{{ConteudoPost}}',$Parsedown->text($post['Conteudo']),$ConteudoTotal);
    $ConteudoTotal = str_replace('{{DataPost}}',$post['DataPost'],$ConteudoTotal);
    //Escreve o conteudo
    fwrite($PostHtml,$ConteudoTotal);
    fclose($PostHtml);
}
$posts = ListarPost(); //Conteudo Retirado do banco de dados
$Posts_Array = array();//Array Para Posts do Blog
$Projetos_Array = array();//Array Para Projetos
//Separando os Posts dos projetos
while ($post = $posts->fetchArray(1)){
    if($post['Projeto'] == 0)
    array_push($Posts_Array,$post);
    else// == 1
    array_push($Projetos_Array,$post);
}
Limpar($GLOBALS['PostsDir']);//Apagando posts antigos
Limpar($GLOBALS['PorjetosDir']);//Apagando Projetos antigos
foreach($Posts_Array as $post)//Escrevdo novos posts
    EscreverArquivo($GLOBALS['PostsDir'],$post,$GLOBALS['TemplateDir'].'/'.'BlogPost.html');
foreach($Projetos_Array as $projetos)//Escrevdo novos projetos
    EscreverArquivo($GLOBALS['PorjetosDir'],$projetos,$GLOBALS['TemplateDir'].'/'.'Projeto.html');
//Gerar index do Blog
$postIndex = '';
//Gerar index dos Projetos 
$projectIndex = '';

echo('<h3>Posts index</h3><br>');
?>
