<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardRouting.class.php 7636 2008-02-27 18:50:43Z fabien $
 */
class sfCrmRouting
{
  static public function addRouteForContact(sfEvent $event)
  {
    $event->getSubject()->prependRoute('sf_crm_contact', new sfDoctrineRouteCollection(array(
        'name'                 => 'sf_crm_contact',
        'model'                => 'sfCrmContact',
        'module'               => 'sfCrmContact',
        'prefix_path'          => 'sf_crm_contact',
        'with_wildcard_routes' => true,
        'requirements'         => array(),
        'collection_actions'   => array('filter' => 'post', 'batch' => 'post')
      )));

  }

  static public function addRouteForContactField(sfEvent $event)
  {
    $event->getSubject()->prependRoute('sf_crm_contact_field', new sfDoctrineRouteCollection(array(
        'name'                 => 'sf_crm_contact_field',
        'model'                => 'sfCrmContactField',
        'module'               => 'sfCrmContactField',
        'prefix_path'          => 'sf_crm_contact_field',
        'with_wildcard_routes' => true,
        'requirements'         => array(),
        'collection_actions'   => array('filter' => 'post', 'batch' => 'post')
      )));
  }
}