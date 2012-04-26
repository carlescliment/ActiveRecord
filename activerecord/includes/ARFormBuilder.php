<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ARFormBuilder
 *
 * @author carles
 */
class ARFormBuilder {
    public static function build(ActiveRecord $instance) {
      $table = MetadataExtractor::getTable($instance);
      $schema = drupal_get_schema($table);
      $form = array();
      foreach ($schema['fields'] as $field_name => $field_data) {
        if ($field_name != 'id') {
          $form[$field_name] = self::renderField($field_data, $schema['fields'][$field_name]);
          if (isset($instance->$field_name)) {
            $form[$field_name]['#default_value'] = $instance->$field_name;
          }
          $form[$field_name]['#title'] = ucfirst($field_name);
        }
      }
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
      );
      return $form;
    }


    private static function renderField($field_data, $schema_data) {
      $form_item = array();
      $form_item['#type'] = self::getFieldType($field_data['type']);
      if (isset($schema_data['length'])) {
        $form_item['#maxlength'] = $schema_data['length'];
      }
      $form_item['#required'] = (isset($schema_data['ar_formbuilder']['required']) && $schema_data['ar_formbuilder']['required']);
      if (isset($schema_data['default'])) {
        $form_item['#default_value'] = $schema_data['default'];
      }
      return $form_item;
    }

    private static function getFieldType($type) {
      switch ($type) {
        case 'varchar':
          return 'textfield';
        case 'text':
          return 'textarea';
      }
      return 'markup';
    }
}


?>
