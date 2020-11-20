<?php
    include('./DataBaseConnector.php');
    if(isset($_GET["Delete"])){//Apagando post
        deletePost($_GET["Delete"]);
    }else{//Criando ou alterando
        //Pegar valores do GET
        $Titulo = $_GET["Titulo"];//Titulo da postagem
        $Subtitulo = $_GET["SubTitulo"];//SubTitulo da postagem
        $Data = $_GET["Data"];//Data da postagem
        $Conteudo = $_GET["Conteudo"];//Mark down do conteudo
        $codigo = $_GET["codigo"];//codigo do post
        $Tipo = 'false'; //Tipo da postagem blog ou projeto
        if(isset($_GET["Tipo"])){
            $Tipo = 'true';
        }
        AlteraInserirPost($Titulo,$Subtitulo,$Data,$Conteudo,$Tipo,$codigo);
    }
    header('Location: ./Blogeditor.php?Editor=Listar')
?>