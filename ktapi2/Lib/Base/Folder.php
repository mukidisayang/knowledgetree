<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Base_Folder extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('folders');
    $this->hasColumn('id', 'integer', 4, array('unsigned' => 0, 'primary' => true, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('name', 'string', 255, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('description', 'string', 255, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('parent_id', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('creator_id', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('is_public', 'integer', 1, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('parent_folder_ids', 'string', null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('full_path', 'string', null, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('permission_object_id', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('permission_lookup_id', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('restrict_document_types', 'integer', 1, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('owner_id', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('depth', 'integer', 2, array('unsigned' => 0, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Base_Folder as Folder', array('local' => 'parent_id', 'foreign' => 'id'));

    $this->hasMany('Base_Document as Document', array('local' => 'id', 'foreign' => 'folder_id'));
  }

}