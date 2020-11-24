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
function escreveItemIdex($Titulo,$Subtitulo,$Data,$Link){
    return '<div class="list-group">
                    <a href="'.$Titulo.'.html" class="list-group-item list-group-item-action flex-column align-items-start active">
                   <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">'.$Titulo.'</h5>
                        <small>Postado em: '.$Data.'</small>
                    </div>
                    <p class="mb-1">'.$Subtitulo.'</p>
                </a>
            </div>';
}
function escrverIndex($dir,$ConteudoArray,$TempleteFile){
    //Geração dos indexs
    $IndexFile = fopen($dir.'/'.str_replace('./','',$dir).'.html','x');
    $pagina = file_get_contents($TempleteFile);
    $index = '';
    foreach($ConteudoArray as $Post){
        $index = $index.escreveItemIdex($Post['Titulo'],$Post['Subtitulo'],$Post['DataPost'],'Test'); 
    }
    $pagina = str_replace('{{Index}}',$index,$pagina);
    fwrite($IndexFile,$pagina);
    fclose($IndexFile);
}
$Posts_Array = arrayPost();//Array Para Posts do Blog
$Projetos_Array = arrayProjetos();//Array Para Projetos
//Separando os Posts dos projetos
Limpar($GLOBALS['PostsDir']);//Apagando posts antigos
Limpar($GLOBALS['PorjetosDir']);//Apagando Projetos antigos
foreach($Posts_Array as $post)//Escrevdo novos posts
    EscreverArquivo($GLOBALS['PostsDir'],$post,$GLOBALS['TemplateDir'].'/'.'BlogPost.html');
foreach($Projetos_Array as $projetos)//Escrevdo novos projetos
    EscreverArquivo($GLOBALS['PorjetosDir'],$projetos,$GLOBALS['TemplateDir'].'/'.'Projeto.html');

//Gerar index do Blog
escrverIndex($GLOBALS['PostsDir'],$Posts_Array,$GLOBALS['TemplateDir'].'/'.'indexBlog.html');
//Gerar index dos Projetos 
escrverIndex($GLOBALS['PorjetosDir'],$Projetos_Array,$GLOBALS['TemplateDir'].'/'.'IndexProjetos.html');
header('Location: ./index.html')
?>
