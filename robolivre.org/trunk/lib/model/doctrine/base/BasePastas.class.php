<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Pastas', 'doctrine');

/**
 * BasePastas
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_pasta
 * @property integer $id_usuario
 * @property integer $id_tipo_conjunto
 * @property integer $id_conjunto
 * @property string $nome
 * @property string $descricao
 * @property integer $tipo_pasta
 * 
 * @method integer getIdPasta()          Returns the current record's "id_pasta" value
 * @method integer getIdUsuario()        Returns the current record's "id_usuario" value
 * @method integer getIdTipoConjunto()   Returns the current record's "id_tipo_conjunto" value
 * @method integer getIdConjunto()       Returns the current record's "id_conjunto" value
 * @method string  getNome()             Returns the current record's "nome" value
 * @method string  getDescricao()        Returns the current record's "descricao" value
 * @method integer getTipoPasta()        Returns the current record's "tipo_pasta" value
 * @method Pastas  setIdPasta()          Sets the current record's "id_pasta" value
 * @method Pastas  setIdUsuario()        Sets the current record's "id_usuario" value
 * @method Pastas  setIdTipoConjunto()   Sets the current record's "id_tipo_conjunto" value
 * @method Pastas  setIdConjunto()       Sets the current record's "id_conjunto" value
 * @method Pastas  setNome()             Sets the current record's "nome" value
 * @method Pastas  setDescricao()        Sets the current record's "descricao" value
 * @method Pastas  setTipoPasta()        Sets the current record's "tipo_pasta" value
 * 
 * @package    robolivre
 * @subpackage model
 * @author     Max Guenes
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePastas extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('pastas');
        $this->hasColumn('id_pasta', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('id_usuario', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('id_tipo_conjunto', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('id_conjunto', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('nome', 'string', 45, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 45,
             ));
        $this->hasColumn('descricao', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('tipo_pasta', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}