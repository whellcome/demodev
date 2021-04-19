<?php

namespace Drupal\natura_local_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class NLSearchPageController extends ControllerBase {

  public function searchPage(Request $request) {
    $keys = $request->query->get('keys');
    $view = \Drupal\views\Views::getView('nl_search');
    $view->setDisplay('default');
    $view->execute();
    $result = $view->result;
    $places = array();
    $roles = array();
//    $fieldsByType = array(
//      'town' =>  \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'town'),
//      'route' => \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'route'),
//    );
//    $fields = _natura_local_search_get_names();
    foreach ($result as $key => $value) {
      $entity = $value->_object->getValue();
      $type = $entity->get('type')->getValue()[0]['target_id'];
      $nid = $entity->get('nid')->getValue()[0]['value'];
      $roles[$nid] = $entity->getOwner()->hasRole('administrator') ? 'admin' : 'user';
      $places[$type][$nid]['rating'] =  \Drupal::service('plugin.manager.votingapi.resultfunction')->getResults('node', $nid);
//      if($type != 'town'){
//        if($type == 'route'){
//          _natura_local_search_fill_places($entity, $type, $fields, $fieldsByType, $places);
//        }
//        $query = \Drupal::entityQuery('node')
//          ->condition('type', array('town','route'), 'IN')
//          ->condition('status', 1)
//          ->condition($fields[$type], $entity->get('nid')->getValue()[0]['value']);
//        $nids = $query->execute();
//        foreach ($nids as $id => $nid){
//          if(!isset($places['town'][$nid]) && !isset($places['town'][$nid])){
//            $entity = Node::load($nid);
//            $type = $entity->get('type')->getValue()[0]['target_id'];
//            _natura_local_search_fill_places($entity, $type, $fields, $fieldsByType, $places);
//          }
//        }
//      }
//      else{
//        _natura_local_search_fill_places($entity, $type, $fields, $fieldsByType, $places);
//      }
//    }
//    $arg = '';
//    $first = FALSE;
//    foreach($places as $place){
//      foreach($place as $nid => $val){
//        if($first){
//          $arg .= '+'.$nid;
//        }
//        else{
//          $arg = $nid;
//          $first = TRUE;
//        }
//      }
    }
    //debug($places);
//    foreach ($places as $type => $nids){
//      $places[$type] = implode('+',$nids);
//    }
    $fields = array(
      'town' => 'destins',
      'route' => 'routes',
      'visit' => 'visites',
      'experience' => 'experiences',
      'point_of_interest' => 'services',
    );
//    $maps=array();
//    foreach ($fields as $key => $val){
//      if(isset($places[$key])) {
//        $maps[$val] = views_embed_view('piois_etc', 'default', $places[$key]);
//      }
//      else{
//        $maps[$val] = views_embed_view('piois_etc', 'default');
//      }
//    }
    $nl_search = array(
      'destins' => views_embed_view('nl_search', 'page_1'),
      'routes' => views_embed_view('nl_search', 'page_2'),
      'visits' => views_embed_view('nl_search', 'page_3'),
      'experiences' => views_embed_view('nl_search', 'page_4'),
      'services' => views_embed_view('nl_search', 'page_5'),
    );
    $output =  array(
      '#theme' => 'natura_local_search',
      '#keys' => $keys,
//      '#places' => $places,
      '#form' => \Drupal::formBuilder()->getForm('Drupal\natura_local_sform\Form\AutocompleteSForm'),
      //'#map' => $maps,
      '#view' => $nl_search,
    );
    $output['#attached']['library'][] = 'natura_local_search/nl-search';
    $output['#attached']['library'][] = 'natura_local_search/nl-maps';
    $output['#attached']['drupalSettings']['nls']['keys'] = $keys;
    $output['#attached']['drupalSettings']['nls']['roles'] = $roles;
    $output['#attached']['drupalSettings']['nls']['mark_imgs'] = _natura_local_search_get_mark_images();
    //$module_handler = \Drupal::service('module_handler');
    $element['#markup'] = '<br>' . ' : ';
    return $output;
  }

}
