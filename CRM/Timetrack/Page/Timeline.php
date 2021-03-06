<?php

class CRM_Timetrack_Page_Timeline extends CRM_Core_Page {
  function run() {
    Civi::resources()
      ->addScriptFile('coop.symbiotic.timetrack', 'js/timeline.js')
      ->addScriptFile('coop.symbiotic.timetrack', 'dist/dhtmlxscheduler/codebase/dhtmlxscheduler.js')
      ->addScriptFile('coop.symbiotic.timetrack', 'dist/dhtmlxscheduler/codebase/ext/dhtmlxscheduler_timeline.js')
      ->addStyleFile('coop.symbiotic.timetrack', 'dist/dhtmlxscheduler/codebase/dhtmlxscheduler.css');

    // TODO: currently we only show a timeline for the current user.
    // Get info on the current user.
    $users = array();
    $session = CRM_Core_Session::singleton();

    $contact = civicrm_api3('Contact', 'getsingle', array(
      'contact_id' => $session->get('userID'),
    ));

    $users[] = array(
      'key' => $session->get('userID'),
      'label' => $contact['display_name'],
    );

    // Get info on cases (contracts) and possible tasks.
    // then reformat the array for the format required by timeline.
    $tasks = array();
    $tmp = CRM_Timetrack_Utils::getCaseActivityTypes();

    foreach ($tmp as $key => $val) {
      $tasks[] = array(
        'key' => $key,
        'label' => $val,
      );
    }

    CRM_Core_Resources::singleton()->addSetting(array(
      'timetrack' => array(
        'users' => $users,
        'tasks' => $tasks,
      )
    ));

    parent::run();
  }
}
