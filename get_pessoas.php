<?php

    session_start();

    if(!$_SESSION['usuario']){
		header('Location: index.php?erro=1');
	}

    require_once('db.class.php');

    $id_usuario = $_SESSION['id_usuario'];
    $nome_pessoa = $_POST['nome_pessoa'];

    $objDb = new db();
    $link = $objDb->conecta_mysql();
    
    
    $sql =" SELECT u.*,us.* ";
    $sql.=" FROM usuarios AS u ";
    $sql.=" LEFT JOIN usuarios_seguidores AS us ";
    $sql.=" ON (us.id_usuario=$id_usuario AND u.id = us.seguindo_id_usuario) ";
    $sql.=" WHERE u.usuario like '%$nome_pessoa%' AND u.id <>$id_usuario ";

    

    $resultado_id = mysqli_query($link, $sql);

    if($resultado_id){

        while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){

            echo '<a href="#" class="list-group-item">';
            	echo '<strong>'.$registro['usuario'].'</strong> <small> - '.$registro['email'].' </small>';
                echo '<p class="list-group-item-text pull-right">'; 
                    

                    $esta_seguindo_usuario_sn = isset($registro['id_usuario_seguidor']) && !empty($registro['id_usuario_seguidor']) ? 'S': 'N';

                    $btn_seguir_display = 'block';
                    $btn_deixar_seguir_display = 'block';

                    if($esta_seguindo_usuario_sn=='N'){

                        $btn_deixar_seguir_display ='none';
                    }else{

                        $btn_seguir_display = 'none';
                    }

                    //botão seguir
                    echo '<button type="button" style="display:'.$btn_seguir_display.'" id="btn_seguir_'.$registro['id'].'" class="btn btn-default btn_seguir" data-id_usuario="'.$registro['id'].'">Seguir</button>';

                    //botão deixar de seguir
                    echo '<button type="button" id="btn_deixar_seguir_'.$registro['id'].'" style="display:'.$btn_deixar_seguir_display.'" class="btn btn-primary btn_deixar_seguir" data-id_usuario="'.$registro['id'].'">Deixar de Seguir</button>';
                echo '</p>';
                echo '<div class="clearfix"></div>';
            echo '</a>';

        }

    }else{
    
        echo 'Erro na consulta de usuarios no banco de dados!';

    }

?>