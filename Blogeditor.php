<?php
include('./DataBaseConnector.php');
function drawEditor($titulo,$subtitulo,$data,$conteudo,$Tipo,$codigo) {
    if($Tipo == "1")
        $checkbox = 'checked="checked"';
    else
        $checkbox = '';
    $editor =
    '<h2 class="text-center mb-2">Editando '.$titulo.'🖊️</h2>
    <form action="/BlogHandler.php" class="mx-auto px-2 py-3" style="height: fit-content; width: 80%; background-color: #292831; color: #ee8695;border: 5px solid #ee8695; border-radius: 10px;">
        <div class="form-group">
            <label for="Titulo">Titulo🔎:</label><br>
            <input value="'.$titulo.'" type="text" id="Titulo" name="Titulo" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="SubTitulo">Subtitulo👀:</label><br>
            <input value="'.$subtitulo.'" type="text" id="SubTitulo" name="SubTitulo" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="Data">Data🗓️:</label><br>
            <input value="'.$data.'" id="Data" name="Data" type="date" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="Conteudo">Conteudo MarkDown📄:</label><br>
            <textarea id="Conteudo" name="Conteudo" cols="40" rows="5" class="form-control">'.$conteudo.'</textarea><br>
        </div>
        <div class="form-check">
            <input '.$checkbox.' type="checkbox" id="Tipo" name="Tipo" value="projeto" class="form-check-input"><label class="form-check-label" for="Tipo"> É um projeto👨‍💻?:</label><br>
        </div><br>
        <input type="hidden" name="codigo" id="codigo" value="'.$codigo.'">
        <input type="submit" value="Submit" class="btn btn-lg btn-block" style="background-color: #4a7a96; color: #fbbbad;">
    </form>';
    return $editor;
}
?>
<html>
<header>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.5.1.slim.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <title>Editor do blog</title>
    <script type="text/javascript">
        function deletePost(postcode) {
            if(confirm("Isso irar apagar o post PARA SEMPRE!!"))
                window.location.href = './BlogHandler.php?Delete='+postcode+'';
        }
    </script>
</header>

<body style="height: 100vh; width: 100vw; background-color: #333f58;">
    <nav class="float-none py-1 mx-auto px-2" style="max-height: fit-content; min-height: 100vh; width: 75vw; background-color: #292831; color: #ee8695;">
        <h1 class="text-center mb-5">📓Blog Editor</h1>
        <?php
        if(isset($_GET["Editor"])){
            echo('<a href="./Blogeditor.php" class="btn btn-lg btn-block" style="background-color: #4a7a96; color: #fbbbad;">Voltar↩️</a></br>');
            switch($_GET["Editor"]){
                case "Editar":
                    //Mostrar editor
                    //Caso tiver dados de post existente mostras
                    if(isset($_GET["id"])){
                        $postFunded = BuscarPost($_GET["id"]);
                        echo(drawEditor($postFunded['Titulo'],$postFunded['Subtitulo'],$postFunded['DataPost'],$postFunded['Conteudo'],$postFunded['Projeto'],$postFunded['codigo']));
                    }else{
                        echo(drawEditor("","","","","",""));
                    }  
                break;
                case "Listar":
                    //Lista posts
                    $result = ListarPost();
                    echo('<table class="table table-striped table-dark" style="width:98%">
                        <tr>
                            <th>Titulo🔎</th>
                            <th>Data🗓️</th>
                            <th>Projeto👨‍💻</th>
                            <th style="text-align: center;">🖊️</th>
                            <th style="text-align: center;">🗑️</th>
                        </tr>'
                    );
                    $total = 0;
                    while ($post = $result->fetchArray(1)){
                        $Projeto = 'Não';
                        if($post['Projeto'] != 0) $Projeto = 'Sim';
                        $editLink = '<a href="./Blogeditor.php?Editor=Editar&id='.$post['codigo'].'" class="btn-sm btn-block" style="background-color: #4a7a96; color: #fbbbad; text-align: center;">Editar🖊️</a>';
                        $deleteLink = '<button onclick="deletePost('.$post['codigo'].')" class="btn-sm btn-block" style="background-color: #ee8695; color: #292831; text-align: center;">Apagar🗑️</button>';
                        echo('
                        <tr>
                            <td>'.$post['Titulo'].'</td>
                            <td>'.$post['DataPost'].'</td>
                            <td>'.$Projeto.'</td>
                            <td>'.$editLink.'</td>
                            <td>'.$deleteLink.'</td>
                        </tr>'
                        );
                        $total++;
                    }
                    echo('</table> <br> <h2 class="text-center mb-2">Numero total de post: '.$total.'</h2>');
                break;
                default:
                    header('Location: ./Blogeditor.php');
            }
        }else{
            
            $PostCount = count(arrayPost());
            $ProjetoCount = count(arrayProjetos());
            echo('<h2 class="text-center mb-2">Numero todal de post: '.$PostCount.'</h2>
                  <h2 class="text-center mb-2">Numero todal de projetos: '.$ProjetoCount.'</h2>');
            
            echo('<a href="./Blogeditor.php?Editor=Editar" class="btn btn-lg btn-block" style="background-color: #4a7a96; color: #fbbbad;">Criar Novo Post🌟</a></br>
                  <a href="./Blogeditor.php?Editor=Listar" class="btn btn-lg btn-block" style="background-color: #4a7a96; color: #fbbbad;">Editar Post Existete🖊️</a></br>
                  <a href="./BlogGenerator.php" class="btn btn-lg btn-block" style="background-color: #4a7a96; color: #fbbbad;">Gerar Blog🖼️</a></br>'
            );
        }
        ?>

    </nav>
</body>

</html>