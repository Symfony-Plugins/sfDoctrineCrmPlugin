<?php
class sfCrmContactFormAdmin extends BasesfCrmContactFormAdmin
{
  public function configure()
  {
    unset($this['updated_at'], $this['created_at']);

    $fields = Doctrine_Query::create()
      ->from('sfCrmContactField f')
      ->leftJoin('f.Values v WITH v.sf_crm_contact_id = ?', $this->object['id'])
      ->execute();

    foreach ($fields as $field)
    {
      if (count($field['Values']))
      {
        $fieldValue = $field['Values'][0];
      } else {
        $fieldValue = new sfCrmContactFieldValue();
        $fieldValue->Field = $field;
        $fieldValue->Contact = $this->object;
      }

      $fieldValueForm = new sfCrmContactFieldValueForm($fieldValue);
      $this->addFieldWidgetAndValidator($fieldValueForm, $field);

      unset($fieldValueForm['id'], $fieldValueForm['sf_crm_contact_field_id'], $fieldValueForm['sf_crm_contact_id']);

      $this->embedForm($field['name'], $fieldValueForm);
    }

    if (!$this->object->exists())
    {
      $this->object->Addresses[] = new sfCrmContactAddress();
      $this->object->Phonenumbers[] = new sfCrmContactPhonenumber();
    }

    foreach ($this->object->Addresses as $address)
    {
      $addressForm = new sfCrmContactAddressForm($address);
      $addressForm->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
      unset($addressForm['id'], $addressForm['sf_crm_contact_id']);
      $this->embedForm('Address ' . $address['id'], $addressForm);
    }

    foreach ($this->object->Phonenumbers as $phonenumber)
    {
      $phonenumberForm = new sfCrmContactPhonenumberForm($phonenumber);
      $phonenumberForm->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
      unset($phonenumberForm['id'], $phonenumberForm['sf_crm_contact_id']);
      $this->embedForm('Phonenumber ' . $phonenumber['id'], $phonenumberForm);
    }
  }

  protected function addFieldWidgetAndValidator(sfFormDoctrine $form, sfCrmContactField $field)
  {
    $widgetClassName = 'sfWidgetForm' . $field['widget_type'];
    $validatorClassName = 'sfValidator' . $field['widget_type'];
    if (!class_exists($validatorClassName))
    {
      $validatorClassName = 'sfValidatorString';
    }

    $widgetOptions = array();
    $widgetAttributes = array();

    $validatorOptions = array();
    $validatorMessages = array();

    switch ($field['widget_type'])
    {
      case 'Choice':
      case 'Select':
        $widgetOptions['choices'] = array('' => '', 'choice_1' => 'Choice 1', 'choice_2' => 'Choice 2');
        break;
  
      case 'SelectCheckbox':
      case 'SelectRadio':
      case 'ChoiceMany':
      case 'SelectMany':
        $widgetOptions['choices'] = array('choice_1' => 'Choice 1', 'choice_2' => 'Choice 2');
      break;
    }

    switch ($field['widget_type'])
    {
      case 'InputCheckbox':
        $validatorClassName = 'sfValidatorBoolean';
        break;

      case 'ChoiceMany':
      case 'SelectCheckbox':
      case 'SelectMany':
        $validatorClassName = 'sfValidatorChoiceMany';
        break;

      case 'Choice':
      case 'Select':
      case 'SelectRadio':
        $validatorClassName = 'sfValidatorChoice';
        break;
    }

    if ($validatorClassName == 'sfValidatorChoice' || $validatorClassName == 'sfValidatorChoiceMany')
    {
      $validatorOptions['choices'] = array_keys($widgetOptions['choices']);
    }

    $validatorOptions['required'] = $field['is_required'];

    $form->widgetSchema['value'] = new $widgetClassName($widgetOptions, $widgetAttributes);
    $form->validatorSchema['value'] = new $validatorClassName($validatorOptions, $validatorMessages);
  }
}