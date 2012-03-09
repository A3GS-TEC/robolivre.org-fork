<?php

/**
 * PublicacoesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PublicacoesTable extends Doctrine_Table {

    /**
     * Returns an instance of this class.
     *
     * @return object PublicacoesTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('Publicacoes');
    }

    public function publicar(Publicacoes $publicacao) {

        $id_usuario = $publicacao->getIdUsuario();
        $id_conteudo = Util::validaNullInserBanco($publicacao->getIdConteudo()); //==""? 'null':$publicacao->getIdConteudo();
        $id_tipo_conjunto = Util::validaNullInserBanco($publicacao->getIdTipoConjunto());//==""? 'null':$publicacao->getIdTipoConjunto();
        $id_conjunto = Util::validaNullInserBanco($publicacao->getIdConjunto());//==""? 'null':$publicacao->getIdConjunto();
        $id_diario_bordo = Util::validaNullInserBanco($publicacao->getIdDiarioBordo());//==""? 'null':$publicacao->getIdDiarioBordo();
        $id_pasta = Util::validaNullInserBanco($publicacao->getIdPasta());//==""? 'null':$publicacao->getIdPasta();
        $id_video = Util::validaNullInserBanco($publicacao->getIdVideo());//==""? 'null':$publicacao->getIdVideo();
        $id_imagem = Util::validaNullInserBanco($publicacao->getIdImagem());//==""? 'null':$publicacao->getIdImagem();
        $id_usuario_original = Util::validaNullInserBanco($publicacao->getIdUsuarioOriginal());//==""? 'null':$publicacao->getIdUsuarioOriginal();
        $id_publicacao_original = Util::validaNullInserBanco($publicacao->getIdPublicacaoOriginal());//==""? 'null':$publicacao->getIdPublicacaoOriginal();
        $id_usuario_referencia = Util::validaNullInserBanco($publicacao->getIdUsuarioReferencia());//==""? 'null':$publicacao->getIdUsuarioReferencia();
        $comentario = $publicacao->getComentario();
        $link = Util::validaNullInserBanco($publicacao->getLink());//==""? 'null':$publicacao->getLink();
        $data_publicacao = $publicacao->getDataPublicacao();
        
        $is_criacao_conjunto = "";
        if($publicacao->getIsCriacaoConjunto()!=null && $publicacao->getIsCriacaoConjunto()!= ""){
            $is_criacao_conjunto = $publicacao->getIsCriacaoConjunto(); 
        }
        
        $query = "INSERT INTO publicacoes 
            (id_usuario,
                id_conteudo,
                id_tipo_conjunto,
                id_conjunto,
                id_diario_bordo,
                id_pasta,
                id_video,
                id_imagem,
                id_usuario_original,
                id_publicacao_original,
                id_usuario_referencia,
                comentario,
                link,
                data_publicacao
                ".$is_criacao_conjunto!=""?",is_criacao_conjunto":"".")
            VALUES ($id_usuario,
                $id_conteudo,
                $id_tipo_conjunto,
                $id_conjunto,
                $id_diario_bordo,
                $id_pasta,
                $id_video,
                $id_imagem,
                $id_usuario_original,
                $id_publicacao_original,
                $id_usuario_referencia,
                $comentario,
                $link,
                $data_publicacao
                ".$is_criacao_conjunto!=""?",$is_criacao_conjunto":"".")";
        $connection = Doctrine_Manager::getInstance()
                        ->getCurrentConnection()->getDbh();
        // Get Connection of Database  
        $statement = $connection->prepare($query);
        // Make Statement  
        $statement->execute();
    }
    
    public function getPublicacoesDoConjunto($id_conjunto) {
        $arrayRetorno = array();
        
        if(!isset($id_conjunto)){
            return array();
        }
        
        $query = "SELECT p.*,u.nome,u.imagem_perfil,p.id_usuario,i.imagem_perfil AS \"imagem_perfil_conjunto\",
        IF (i.id_tipo_conjunto = 1,con.nome,com.nome) as \"nome_conjunto\"
        FROM publicacoes p 
        LEFT JOIN usuarios u ON u.id_usuario = p.id_usuario
        LEFT JOIN conjuntos i ON p.id_conjunto = i.id_conjunto
        LEFT JOIN conteudos con ON con.id_tipo_conjunto = i.id_tipo_conjunto AND con.id_conjunto = i.id_conjunto 
        LEFT JOIN comunidades com ON com.id_tipo_conjunto = i.id_tipo_conjunto AND com.id_conjunto = i.id_conjunto 
        WHERE p.visivel =  1 AND p.id_conjunto = $id_conjunto
        ORDER BY p.data_publicacao DESC";
        
        $connection = Doctrine_Manager::getInstance()
                        ->getCurrentConnection()->getDbh();
        // Get Connection of Database  

        $statement = $connection->prepare($query);
        // Make Statement  

        $statement->execute();
        // Execute Query  

        $resultado = $statement->fetchAll();
        
        if ($resultado) {
            foreach ($resultado as $reg) {

                //se já existe um objeto instanciado com esse id, não precisa instanciar novamente
                if (isset($arrayRetorno[$reg['id_publicacao']])) {
                    $publicacao = $arrayRetorno[$reg['id_publicacao']];
                } else {
                    $publicacao = new Publicacoes();
                }

                $publicacao->setDataPublicacao($reg['data_publicacao']);
                $publicacao->setLink($reg['link']);
                $publicacao->setComentario($reg['comentario']);
                $publicacao->setIdUsuarioReferencia($reg['id_usuario_referencia']);
                $publicacao->setIdPublicacaoOriginal($reg['id_publicacao_original']);
                $publicacao->setIdUsuarioOriginal($reg['id_usuario_original']);
                $publicacao->setIdImagem($reg['id_imagem']);
                $publicacao->setIdVideo($reg['id_video']);
                $publicacao->setIdPasta($reg['id_pasta']);
                $publicacao->setIdDiarioBordo($reg['id_diario_bordo']);
                $publicacao->setIdConjunto($reg['id_conjunto']);
                $publicacao->setIdTipoConjunto($reg['id_tipo_conjunto']);
                $publicacao->setIdConteudo($reg['id_conteudo']);
                $publicacao->setIdUsuario($reg['id_usuario']);
                $publicacao->setIdPublicacao($reg['id_publicacao']);
                $publicacao->setNomeUsuario($reg['nome']);
                $publicacao->setImagemPerfilUsuario($reg['imagem_perfil']);
                $publicacao->setNomeConjunto($reg['nome_conjunto']);
                $publicacao->setTipoPublicacao($reg['tipo_publicacao']);
                $publicacao->setImagemPerfilConjunto($reg['imagem_perfil_conjunto']);
                
                //É um comentário de uma publicação
                if ($publicacao->getIdPublicacaoOriginal() != null && $publicacao->getIdUsuarioOriginal() != null) {

                    //se no array, existir a publicação original, é so adicionar o comentario no objeto
                    if (isset($arrayRetorno[$publicacao->getIdPublicacaoOriginal()])) {
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()]->adicionarPublicacaoComentario($publicacao);
                    }//senão, cria um objeto temporário
                    else {
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()] = new Publicacoes();
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()]->adicionarPublicacaoComentario($publicacao);
                    }
                } else {
                    $arrayRetorno[$publicacao->getIdPublicacao()] = $publicacao;
                }
            }
            
            foreach(array_keys($arrayRetorno) as $chave){
                if($arrayRetorno[$chave]->getIdPublicacao() == ""){
                    $array = $arrayRetorno[$chave]->getGrupoComentarios();
                    $arrayRetorno[$chave] = $this->findOneBy("id_publicacao", $array[0]->getIdPublicacaoOriginal());
                    $arrayRetorno[$chave]->setGrupoComentarios(array_reverse($array));
                }
            }
        }

        return $arrayRetorno;
    }
    
    
    
    public function getPublicacoesHome() {
        
        $arrayRetorno = array('conteudos'=>array(),'amigos'=> array());
        $id_usuario_logado  = UsuarioLogado::getInstancia()->getIdUsuario();     
        $query = "SELECT p.*,u.nome,u.imagem_perfil,p.id_usuario,
        r.nome AS \"nome_usuario_referencia\",i.imagem_perfil AS \"imagem_perfil_conjunto\",
        IF (i.id_tipo_conjunto = 1,con.nome,com.nome) as \"nome_conjunto\"
        FROM publicacoes p 
        LEFT JOIN amigos a ON a.id_usuario_a = p.id_usuario OR a.id_usuario_b = p.id_usuario
        LEFT JOIN usuarios u ON u.id_usuario = p.id_usuario
        LEFT JOIN conjuntos i ON p.id_conjunto = i.id_conjunto
        LEFT JOIN conteudos con ON con.id_tipo_conjunto = i.id_tipo_conjunto AND con.id_conjunto = i.id_conjunto 
        LEFT JOIN comunidades com ON com.id_tipo_conjunto = i.id_tipo_conjunto AND com.id_conjunto = i.id_conjunto 
        LEFT JOIN usuarios r ON p.id_usuario_referencia = r.id_usuario
        WHERE p.visivel =  1 AND (a.id_usuario_a = $id_usuario_logado OR a.id_usuario_b = $id_usuario_logado) AND a.aceito = 1
        ORDER BY p.data_publicacao DESC";
        
        $connection = Doctrine_Manager::getInstance()
                        ->getCurrentConnection()->getDbh();
        // Get Connection of Database  

        $statement = $connection->prepare($query);
        // Make Statement  

        $statement->execute();
        // Execute Query  

        $resultado = $statement->fetchAll();
        
//        Util::pre($resultado);
        
        if ($resultado) {
            foreach ($resultado as $reg) {

                //se já existe um objeto instanciado com esse id, não precisa instanciar novamente
                if (isset($arrayRetorno[$reg['id_publicacao']])) {
                    $publicacao = $arrayRetorno[$reg['id_publicacao']];
                } else {
                    $publicacao = new Publicacoes();
                }

                $publicacao->setDataPublicacao($reg['data_publicacao']);
                $publicacao->setLink($reg['link']);
                $publicacao->setComentario($reg['comentario']);
                $publicacao->setIdUsuarioReferencia($reg['id_usuario_referencia']);
                $publicacao->setIdPublicacaoOriginal($reg['id_publicacao_original']);
                $publicacao->setIdUsuarioOriginal($reg['id_usuario_original']);
                $publicacao->setIdImagem($reg['id_imagem']);
                $publicacao->setIdVideo($reg['id_video']);
                $publicacao->setIdPasta($reg['id_pasta']);
                $publicacao->setIdDiarioBordo($reg['id_diario_bordo']);
                $publicacao->setIdConjunto($reg['id_conjunto']);
                $publicacao->setIdTipoConjunto($reg['id_tipo_conjunto']);
                $publicacao->setIdConteudo($reg['id_conteudo']);
                $publicacao->setIdUsuario($reg['id_usuario']);
                $publicacao->setIdPublicacao($reg['id_publicacao']);
                $publicacao->setNomeUsuario($reg['nome']);
                $publicacao->setImagemPerfilUsuario($reg['imagem_perfil']);
                $publicacao->setNomeConjunto($reg['nome_conjunto']);
                $publicacao->setNomeUsuarioReferencia($reg['nome_usuario_referencia']);
                $publicacao->setTipoPublicacao($reg['tipo_publicacao']);
                $publicacao->setImagemPerfilConjunto($reg['imagem_perfil_conjunto']);
                
                //É um comentário de uma publicação
                if ($publicacao->getIdPublicacaoOriginal() != null && $publicacao->getIdUsuarioOriginal() != null) {

                    //se no array, existir a publicação original, é so adicionar o comentario no objeto
                    if (isset($arrayRetorno[$publicacao->getIdPublicacaoOriginal()])) {
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()]->adicionarPublicacaoComentario($publicacao);
                    } //senão, cria um objeto temporário
                    else {
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()] = new Publicacoes();
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()]->adicionarPublicacaoComentario($publicacao);
                    }
                } else {
                    if(isset($reg['id_conjunto']) && $reg['id_conjunto']!=""){
                        $arrayRetorno['conteudos'][$publicacao->getIdPublicacao()] = $publicacao;
                    }else{
                        $arrayRetorno['amigos'][$publicacao->getIdPublicacao()] = $publicacao;
                    }
                }
            }
            
            //para cada objeto temporário inserido(caso tenha encontrado um comentario antes da publicação), procurar as publicações originais            
            /*foreach(array_keys($arrayRetorno['conteudos']) as $chave){
                if($arrayRetorno['conteudos'][$chave]->getIdPublicacao() == ""){
                    $array = $arrayRetorno['conteudos'][$chave]->getGrupoComentarios();
                    $arrayRetorno['conteudos'][$chave] = $this->findOneBy("id_publicacao", $array[0]->getIdPublicacaoOriginal());
                    $arrayRetorno['conteudos'][$chave]->setGrupoComentarios(array_reverse($array));
                }
            }
            foreach(array_keys($arrayRetorno['amigos']) as $chave){
                if($arrayRetorno['amigos'][$chave]->getIdPublicacao() == ""){
                    $array = $arrayRetorno['amigos'][$chave]->getGrupoComentarios();
                    $arrayRetorno['amigos'][$chave] = $this->findOneBy("id_publicacao", $array[0]->getIdPublicacaoOriginal());
                    $arrayRetorno['amigos'][$chave]->setGrupoComentarios(array_reverse($array));
                }
            }*/
            
        }

        return $arrayRetorno;
    }
    
    public function getPublicacoesDoPerfil($id_usuario) {
        $arrayRetorno = array();
                    
        $query = "SELECT p.*,u.nome,u.imagem_perfil,p.id_usuario,
        r.nome AS \"nome_usuario_referencia\",i.imagem_perfil AS \"imagem_perfil_conjunto\",
        IF (i.id_tipo_conjunto = 1,con.nome,com.nome) as \"nome_conjunto\"
        FROM publicacoes p 
        LEFT JOIN usuarios u ON u.id_usuario = p.id_usuario
        LEFT JOIN conjuntos i ON p.id_conjunto = i.id_conjunto
        LEFT JOIN conteudos con ON con.id_tipo_conjunto = i.id_tipo_conjunto AND con.id_conjunto = i.id_conjunto 
        LEFT JOIN comunidades com ON com.id_tipo_conjunto = i.id_tipo_conjunto AND com.id_conjunto = i.id_conjunto 
        LEFT JOIN usuarios r ON p.id_usuario_referencia = r.id_usuario
        WHERE p.visivel =  1 AND (p.id_usuario = $id_usuario OR p.id_usuario_original = $id_usuario OR p.id_usuario_referencia = $id_usuario)
        ORDER BY p.data_publicacao DESC";
        
        $connection = Doctrine_Manager::getInstance()
                        ->getCurrentConnection()->getDbh();
        // Get Connection of Database  

        $statement = $connection->prepare($query);
        // Make Statement  

        $statement->execute();
        // Execute Query  

        $resultado = $statement->fetchAll();
        
//        Util::pre($resultado);
        
        if ($resultado) {
            foreach ($resultado as $reg) {

                //se já existe um objeto instanciado com esse id, não precisa instanciar novamente
                if (isset($arrayRetorno[$reg['id_publicacao']])) {
                    $publicacao = $arrayRetorno[$reg['id_publicacao']];
                } else {
                    $publicacao = new Publicacoes();
                }

                $publicacao->setDataPublicacao($reg['data_publicacao']);
                $publicacao->setLink($reg['link']);
                $publicacao->setComentario($reg['comentario']);
                $publicacao->setIdUsuarioReferencia($reg['id_usuario_referencia']);
                $publicacao->setIdPublicacaoOriginal($reg['id_publicacao_original']);
                $publicacao->setIdUsuarioOriginal($reg['id_usuario_original']);
                $publicacao->setIdImagem($reg['id_imagem']);
                $publicacao->setIdVideo($reg['id_video']);
                $publicacao->setIdPasta($reg['id_pasta']);
                $publicacao->setIdDiarioBordo($reg['id_diario_bordo']);
                $publicacao->setIdConjunto($reg['id_conjunto']);
                $publicacao->setIdTipoConjunto($reg['id_tipo_conjunto']);
                $publicacao->setIdConteudo($reg['id_conteudo']);
                $publicacao->setIdUsuario($reg['id_usuario']);
                $publicacao->setIdPublicacao($reg['id_publicacao']);
                $publicacao->setNomeUsuario($reg['nome']);
                $publicacao->setImagemPerfilUsuario($reg['imagem_perfil']);
                $publicacao->setNomeConjunto($reg['nome_conjunto']);
                $publicacao->setNomeUsuarioReferencia($reg['nome_usuario_referencia']);
                $publicacao->setTipoPublicacao($reg['tipo_publicacao']);
                $publicacao->setImagemPerfilConjunto($reg['imagem_perfil_conjunto']);
                
                //É um comentário de uma publicação
                if ($publicacao->getIdPublicacaoOriginal() != null && $publicacao->getIdUsuarioOriginal() != null) {

                    //se no array, existir a publicação original, é so adicionar o comentario no objeto
                    if (isset($arrayRetorno[$publicacao->getIdPublicacaoOriginal()])) {
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()]->adicionarPublicacaoComentario($publicacao);
                    }//senão, cria um objeto temporário
                    else {
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()] = new Publicacoes();
                        $arrayRetorno[$publicacao->getIdPublicacaoOriginal()]->adicionarPublicacaoComentario($publicacao);
                    }
                } else {
                    $arrayRetorno[$publicacao->getIdPublicacao()] = $publicacao;
                }
            }
            
            foreach(array_keys($arrayRetorno) as $chave){
                if($arrayRetorno[$chave]->getIdPublicacao() == ""){
                    $array = $arrayRetorno[$chave]->getGrupoComentarios();
                    $arrayRetorno[$chave] = $this->findOneBy("id_publicacao", $array[0]->getIdPublicacaoOriginal());
                    $arrayRetorno[$chave]->setGrupoComentarios(array_reverse($array));
                }
            }
        }

        return $arrayRetorno;
    }

}