<?php

/**
 * Publicacoes
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    robolivre
 * @subpackage model
 * @author     Max Guenes
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Publicacoes extends BasePublicacoes {

    const PUBLICACAO_COMUM = 0;
    const CRIACAO_CONJUNTO = 1;
    const SEGUIR_CONTEUDO = 2;

    private $grupoComentarios = array();
    private $nomeUsuario;
    private $nomeUsuarioReferencia;
    private $imagemPerfilUsuario;
    private $nomeConjunto;
    private $imagemPerfilConjunto;
    
    public function getImagemPerfilUsuario($tipoImagem = Util::IMAGEM_MEDIA) {
        
        $imagem = $this->imagemPerfilUsuario;

        if (!isset($imagem) || $imagem == "") {
            switch ($tipoImagem) {
                case Util::IMAGEM_GRANDE:
                    return "/assets/img/rl/_avatar-default-140.png";
                case Util::IMAGEM_MEDIA:
                    return "/assets/img/rl/_avatar-default-60.png";
                case Util::IMAGEM_MINIATURA:
                    return "/assets/img/rl/_avatar-default-20.png";
            }
        }else{
            switch ($tipoImagem) {
                case Util::IMAGEM_GRANDE:
                    return "/assets/img/thumbnails/".str_replace(array("#"),array("140"),$imagem);
                case Util::IMAGEM_MEDIA:
                    return "/assets/img/thumbnails/".str_replace(array("#"),array("60"),$imagem);
                case Util::IMAGEM_MINIATURA:
                    return "/assets/img/thumbnails/".str_replace(array("#"),array("20"),$imagem);
            }
        }

        return $imagem;
    }

    public function getImagemPerfilConjunto($tipoImagem = Util::IMAGEM_MINIATURA) {
        $imagem = $this->imagemPerfilConjunto;

        if (!isset($imagem) || $imagem == "") {
            switch ($tipoImagem) {
                case Util::IMAGEM_GRANDE:
                    return "/assets/img/rl/_avatar-default-140.png";
                case Util::IMAGEM_MEDIA:
                    return "/assets/img/rl/_avatar-default-60.png";
                case Util::IMAGEM_MINIATURA:
                    return "/assets/img/rl/_avatar-default-20.png";
            }
        }else{
            switch ($tipoImagem) {
                case Util::IMAGEM_GRANDE:
                    return "/assets/img/thumbnails/".str_replace(array("#"),array("140"),$imagem);
                case Util::IMAGEM_MEDIA:
                    return "/assets/img/thumbnails/".str_replace(array("#"),array("60"),$imagem);
                case Util::IMAGEM_MINIATURA:
                    return "/assets/img/thumbnails/".str_replace(array("#"),array("20"),$imagem);
            }
        }
    }

    public function setImagemPerfilConjunto($imagemPerfilConjunto) {
        $this->imagemPerfilConjunto = $imagemPerfilConjunto;
    }

    public function setImagemPerfilUsuario($imagemPerfilUsuario) {
        $this->imagemPerfilUsuario = $imagemPerfilUsuario;
    }

    public function getNomeUsuarioReferencia() {
        return $this->nomeUsuarioReferencia;
    }

    public function setNomeUsuarioReferencia($nomeUsuarioReferencia) {
        $this->nomeUsuarioReferencia = $nomeUsuarioReferencia;
    }

    public function getNomeConjunto() {
        return $this->nomeConjunto;
    }

    public function setNomeConjunto($nomeConjunto) {
        $this->nomeConjunto = $nomeConjunto;
    }

    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario($nome_usuario) {
        $this->nomeUsuario = $nome_usuario;
    }

    public function getGrupoComentarios() {
        return array_reverse($this->grupoComentarios);
    }

    public function setGrupoComentarios($comentarios) {
        $this->grupoComentarios = $comentarios;
    }

    public function adicionarPublicacaoComentario(Publicacoes $publicacao) {
        $this->grupoComentarios[$publicacao->getIdPublicacao()] = $publicacao;
    }

    public function imprimir($nomeForm = null,$arrayParametrosInclude = null) {
        $string = "";
        if ($this->getTipoPublicacao() == self::PUBLICACAO_COMUM) {
            $string .= "<li class=\"vcard\">";
            $string .= "<a href=\"" . url_for('perfil/exibir?u=' . $this->getIdUsuario()) . "\" class=\"photo\">";
            $string .= "<img src=\"" . image_path($this->getImagemPerfilUsuario()) . "\" alt=\"".$this->getNomeUsuario()."\" title=\"".$this->getNomeUsuario()."\">";
                
            
                 
            //NO CONJUNTO (COMUNIDADE OU CONTEUDO)
            if ($this->getIdConjunto() != null) {
                $string .= "<img src=\"" . $this->getImagemPerfilConjunto() . "\" alt=\"" . $this->getNomeConjunto() . "\" title=\"" . $this->getNomeConjunto() . "\" class=\"sub-icon\">";
                $string .="</a>";
                $string .= "<div class=\"entry\">";       
                $string .= Util::getTagUsuario($this->getNomeUsuario(), $this->getIdUsuario());
                $string .= " publicou em ".Util::getTagConteudo($this->getNomeConjunto(),$this->getIdConjunto(),true).".";
            
            //NO PERFIL DE ALGUEM    
            } else if ($this->getIdUsuarioReferencia() != null) {
                $string .="</a>";
                $string .= "<div class=\"entry\">";       
                $string .= Util::getTagUsuario($this->getNomeUsuario(), $this->getIdUsuario());
                $string .= " EM ";
                $string .= Util::getTagUsuario($this->getNomeUsuarioReferencia(), $this->getIdUsuarioReferencia());
            
            //PUBLICAÇÃO ATUALIZAÇÃO DE STATUS
            }else{
                $string .="</a>";
                $string .= "<div class=\"entry\">";       
            }
            
            $string .= "<p>".Util::getTextoFormatado($this->getComentario())."</p>";
            $string .= "<ul class=\"meta\">";
            $string .= "<li class=\"visivel-para\"><i class=\"icon-eye-open\" title=\"Público\"></i></li>";
            $string .= "<span class=\"time\" title=\"" . Util::getDataFormatada($this->getDataPublicacao()) . "\">" . Util::getDataSimplificada($this->getDataPublicacao()) . "</span>";
            $string .= "</ul>";
            
            $string .= "<ul class=\"comments\">";
            if(count($this->getGrupoComentarios())>0){
                
                foreach ($this->getGrupoComentarios() as $comentario) {
                    $string .= "<li><a href=\"" . url_for('perfil/exibir?u=' . $comentario->getIdUsuario()) . "\" class=\"photo\"><img src=\"" . image_path($this->getImagemPerfilUsuario()) . "\" alt=\"".$comentario->getNomeUsuario()."\" title=\"".$comentario->getNomeUsuario()."\"></a>";
                    $string .= Util::getTagUsuario($comentario->getNomeUsuario(), $comentario->getIdUsuario());
                    $string .= "<div class=\"comment\">";
                    $string .= "<p>".Util::getTextoFormatado($comentario->getComentario())."</p>";
                    $string .= "</div>";
                    $string .= "<a class=\"close\" title=\"Excluir seu comentário\">&times;</a>";
                    $string .= "</li>";
                    
                    
                }
                
                
            }

            //se tem formulário de comentário
            if($nomeForm!=null && $arrayParametrosInclude != null){
                include_partial($nomeForm, $arrayParametrosInclude);
            }

            $string .= "</ul>";
            
            $string .= "</div><!-- entry -->";
            
            
            
            
         /** ATIVIDADES **/   
            
            
        //CRIACAO DE CONTEUDO OU COMUNIODADE    
        } else if ($this->getTipoPublicacao() == self::CRIACAO_CONJUNTO) {
            $string .= "<li class=\"vcard activity\">";
            $string .= "<a href=\"" . url_for('perfil/exibir?u=' . $this->getIdUsuario()) . "\" class=\"photo\"><img src=\"" . image_path($this->getImagemPerfilUsuario(Util::IMAGEM_MINIATURA)) . "\" alt=\"".$this->getNomeUsuario()."\" title=\"".$this->getNomeUsuario()."\"></a>";
            $string .= Util::getTagUsuario($this->getNomeUsuario(), $this->getIdUsuario());
            $string .= " criou ";
            $string .= Util::getTagConteudo($this->getNomeConjunto(), $this->getIdConjunto(),true);
            $string .= ". <span class=\"time\" title=\"" . Util::getDataFormatada($this->getDataPublicacao()) . "\">" . Util::getDataSimplificada($this->getDataPublicacao()) . "</span>";
 
            
        //SEGUINDO CONTEÚDO
        } else if ($this->getTipoPublicacao() == self::SEGUIR_CONTEUDO) {
            $string .= "<li class=\"vcard activity\">";
            $string .= "<a href=\"" . url_for('perfil/exibir?u=' . $this->getIdUsuario()) . "\" class=\"photo\"><img src=\"" . image_path($this->getImagemPerfilUsuario(Util::IMAGEM_MINIATURA)) . "\" alt=\"".$this->getNomeUsuario()."\" title=\"".$this->getNomeUsuario()."\"></a>";
            $string .= Util::getTagUsuario($this->getNomeUsuario(), $this->getIdUsuario());

            $string .= " está seguindo ";
            $string .= Util::getTagConteudo($this->getNomeConjunto(), $this->getIdConjunto(),true);
            $string .= ". <span class=\"time\" title=\"" . Util::getDataFormatada($this->getDataPublicacao()) . "\">" . Util::getDataSimplificada($this->getDataPublicacao()) . "</span>";
        }

        
            
        
            $string .= "<div class=\"btn-group\">";
            $string .= "<a class=\"btn btn-mini dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\" title=\"Opções\">";
            $string .= "<span class=\"icon-share  icon-gray\"></span>";
            $string .= "</a>";
            $string .= "<ul class=\"dropdown-menu\">";
            $string .= "<li>";
            $string .= "<a href=\"#\">Compartilhar no Twitter</a>";
            $string .= "</li>";
            $string .= "<li>";
            $string .= "<a href=\"#\">Compartilhar no Facebook</a>";
            $string .= "</li>";
            $string .= "<li class=\"divider\"></li>";
            $string .= "<li>";
            $string .= "<a href=\"#\"><i class=\"icon-flag\"></i> Reportar abuso</a>";
            $string .= "</li>";
            $string .= "</ul>";
            $string .= "</div>";
        $string .= "</li>";
        echo $string;
    }

}

