<?php
    //Criar tabela caso não existir
    $table = 'CREATE TABLE IF NOT EXISTS "posts" (
                "Titulo"	Text,
                "Subtitulo"	Text,
                "DataPost"	Datetime,
                "Conteudo"	Text,
                "Projeto"	Boolean,
                "codigo"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT
                );';
    $driver = new sqlite3('./Blog.db');
    $driver->query($table);
    //Função retorna todos os posts
    function ListarPost(){
        $driver = new sqlite3('./Blog.db');
        $query = "SELECT * FROM 'posts' ORDER BY datetime('DataPost') ASC";
        return $driver->query($query);
    }
    //Função retorna um post buscado
    function BuscarPost($codigo){
        $result = ListarPost();
        while ($post = $result->fetchArray(1)){
            if($codigo == $post['codigo'])
            return $post;
        }
    }
    //Função Altera ou insere posts
    function AlteraInserirPost($titulo,$subtitulo,$data,$conteudo,$Tipo,$codigo){
        $driver = new sqlite3('./Blog.db');
        if($codigo != "")
            $query = "UPDATE 'posts' 
                      SET 
                        Titulo='".$titulo."',
                        Subtitulo='".$subtitulo."',
                        DataPost='".$data."',
                        Conteudo='".$conteudo."',
                        Projeto=".$Tipo." 
                      WHERE 
                        codigo = ".$codigo."";
        else
            $query = "INSERT OR REPLACE INTO 'posts' 
                        ('Titulo','Subtitulo','DataPost','Conteudo','Projeto') 
                        VALUES ('".$titulo."','".$subtitulo."','".$data."','".$conteudo."',".$Tipo.")";
        echo($query);
        $status = $driver->query($query);
    }
    //Função apaga um post com base no titulo
    function deletePost($codigo){
        $driver = new sqlite3('./Blog.db');
        $query = "DELETE FROM posts 
                    WHERE codigo = ".$codigo."";
        echo($query);
        $status = $driver->query($query);
    }
    function arrayTipo($tipo){
        $array = Array(); 
        $result = ListarPost();
        while ($post = $result->fetchArray(1))
            if($post['Projeto'] == $tipo)
                array_push($array,$post);
        return $array;
    }
    function arrayPost(){
        return arrayTipo(0);
    }
    function arrayProjetos(){
        return arrayTipo(1);
    }
?>  