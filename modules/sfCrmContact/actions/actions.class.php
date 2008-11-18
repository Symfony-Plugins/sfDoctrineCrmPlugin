<?php

require_once dirname(__FILE__).'/../lib/sfCrmContactGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfCrmContactGeneratorHelper.class.php';

/**
 * sfCrmContact actions.
 *
 * @package    symfony12
 * @subpackage sfCrmContact
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sfCrmContactActions extends autoSfCrmContactActions
{
  public function executeListNewPhonenumber(sfWebRequest $request)
  {
    $contact = Doctrine::getTable('sfCrmContact')->find($request->getParameter('id'));
    $this->forward404unless($contact);

    $phonenumber = new sfCrmContactPhonenumber();
    $contact->Phonenumbers[] = $phonenumber;
    $contact->save();

    $this->getUser()->setFlash('notice', 'Blank phonenumber added successfully.');
    $this->redirect('@sf_crm_contact_edit?id=' . $contact['id'] . '#sf_crm_contact_Phonenumber ' . $phonenumber['id'] . '_phonenumber');
  }

  public function executeListNewAddress(sfWebRequest $request)
  {
    $contact = Doctrine::getTable('sfCrmContact')->find($request->getParameter('id'));
    $this->forward404unless($contact);

    $address = new sfCrmContactAddress();
    $contact->Addresses[] = $address;
    $contact->save();

    $this->getUser()->setFlash('notice', 'Blank address added successfully.');
    $this->redirect('@sf_crm_contact_edit?id=' . $contact['id'] . '#sf_crm_contact_Address ' . $address['id'] . '_address_1');
  }
}
