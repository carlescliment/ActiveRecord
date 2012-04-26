<?php

class MetadataExtractor {


  /**
   * Returns the table containing the ActiveRecord instance.
   * @param ActiveRecord $instance
   * @return string table name
   */
  public static function getTable(ActiveRecord $instance) {
    $class_name = get_class($instance);
    return self::getTableFromClassName($class_name);
  }


  public static function getClass(ActiveRecord $instance) {
    return get_class($instance);
  }


  /**
   * Returns the table containing the ActiveRecord class name.
   * @param string $class_name
   * @return string table name
   */
  public static function getTableFromClassName($class_name) {
    $table = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
    return $table . 's';
  }


  public static function getChildrenAttribute(ActiveRecord $instance) {
    $class_name = get_class($instance);
    return self::getChildrenAttributeFromClassName($class_name);
  }


  public static function getChildrenAttributeFromClassName($class_name) {
    $attribute = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
    return '_' . $attribute . 's';
  }


  /**
   * Returns the name of the children class if the given instance is parent. FALSE if it is not.
   * @param ActiveRecord $instance
   */
  public static function getChildrenClass(ActiveRecord $instance) {
    $reflection = new ReflectionAnnotatedClass($instance);
    return TRUE;
    if ($reflection->hasAnnotation('HasMany')) {
      $child_class = $reflection->getAnnotation('HasMany')->value;
      return $child_class;
    }
    return FALSE;
  }


  /**
   * Returns TRUE if the class defines a relationship with children.
   * @param ActiveRecord $instance
   */
  public static function hasChildren(ActiveRecord $instance) {
    return self::getChildrenClass($instance) !== FALSE;
  }


  /**
   * Returns TRUE if the class defines a relationship with parent.
   * @param ActiveRecord $instance
   */
  public static function hasParent(ActiveRecord $instance) {
    return self::getParentClass($instance) !== FALSE;
  }


  /**
   * Returns the name of the parent class if the given instance is children. FALSE if it is not.
   * @param ActiveRecord $instance
   */
  public static function getParentClass(ActiveRecord $instance) {
    $reflection = new ReflectionAnnotatedClass($instance);
    if ($reflection->hasAnnotation('BelongsTo')) {
      $parent_class = $reflection->getAnnotation('BelongsTo')->value;
      return $parent_class;
    }
    return FALSE;
  }


  /**
   * Returns the name of the children function if the given instance is parent. FALSE if it is not.
   * @param ActiveRecord $instance
   */
  public static function getChildrenFunctionName(ActiveRecord $instance) {
    $children_class = self::getChildrenClass($instance);
    return self::getTableFromClassName($children_class);
  }


  public static function getAddChildrenFunctionName(ActiveRecord $instance) {
    $children_class = self::getChildrenClass($instance);
    return 'add' . $children_class;
  }


  public static function getDeleteChildrenFunctionName(ActiveRecord $instance) {
    $children_class = self::getChildrenClass($instance);
    return 'delete' . $children_class . 's';
  }


  /**
   * Returns the name of the parent function if the given instance is child. FALSE if it is not.
   * @param ActiveRecord $instance
   */
  public static function getParentFunctionName(ActiveRecord $instance) {
    $class_name = self::getParentClass($instance);
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
  }


  /**
   * @param ActiveRecord $instance
   * @return string id field in the related model tables.
   */
  public static function getIdField(ActiveRecord $instance) {
    $class_name = get_class($instance);
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name)) . '_id';
  }


  /**
   * @param ActiveRecord $instance
   * @return string id field in the related model tables.
   */
  public static function getParentIdField(ActiveRecord $instance) {
    $class_name = self::getParentClass($instance);
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name)) . '_id';
  }

}