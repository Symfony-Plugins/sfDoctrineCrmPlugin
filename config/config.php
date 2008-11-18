<?php

if (sfConfig::get('app_sf_crm_plugin_routes_register', true))
{
  foreach (array('Contact', 'ContactField') as $name)
  {
    if (in_array('sfCrm' . $name, sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('sfCrmRouting', 'addRouteFor' . $name));
    }
  }
}