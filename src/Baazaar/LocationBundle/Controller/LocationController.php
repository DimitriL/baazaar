<?php
namespace Baazaar\LocationBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Baazaar\LocationBundle\Entity\Country;
use Baazaar\LocationBundle\Entity\Province;
use Baazaar\LocationBundle\Entity\Zipcode;
use Baazaar\LocationBundle\Entity\Place;

class LocationController extends Controller {


    public function importAction() {
      //get excel file

      $file = dirname(__FILE__) . '/../Resources/data/zipcodes_num_nl.xls';
      $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($file);
      $sheetObj = $phpExcelObject->getActiveSheet();

      $places = array();

      $startFrom = 2; //default value is 1
      $limit = 100000; //default value is null
      foreach( $sheetObj->getRowIterator($startFrom, $limit) as $row ){
          foreach( $row->getCellIterator() as $index => $cell ){
              $places[$row->getRowIndex()][] = $cell->getCalculatedValue();
          }
      }
      $this->createLocations($places);
      //var_dump(get_class_methods($phpExcelObject));
    }


    private function createLocations($places) {

        $country = $this->checkCountry('Belgium', 'be');

        foreach($places as $place) {
              $province = $this->checkProvince($place[3], $country);
              $zipcode  = $this->checkZipCode($place[0], $province);
              $place_name = $this->checkPlace($place[1], $zipcode);
        }
    }

    private function checkCountry($country_name, $countryCode) {
      $em = $this->getDoctrine()->getManager();
      $country = $em->getRepository('BaazaarLocationBundle:Country')->findOneBy(array('country_code' => $countryCode));

      if(!$country){
        $country = new Country();
        $country->setName($country_name);
        $country->setCountryCode($countryCode);

        $em->persist($country);
        $em->flush();
      }
      return $country;
    }

    private function checkProvince($province_name, $country) {
      $em = $this->getDoctrine()->getManager();

      if(empty($province_name)) $province_name = 'special';
      $province = $em->getRepository('BaazaarLocationBundle:Province')->findOneBy(array('name' => $province_name));

      if(!$province){

        $province = new Province();
        $province->setName($province_name);
        $province->setCountry($country);

        $em->persist($province);
        $em->flush();
      }
      return $province;
    }

    private function checkZipCode($zip, $province) {
      $em = $this->getDoctrine()->getManager();
      $zipcode = $em->getRepository('BaazaarLocationBundle:Zipcode')->findOneBy(array('zip' => $zip));

      if(!$zipcode){
        $zipcode = new Zipcode();
        $zipcode->setZip($zip);
        $zipcode->setProvince($province);

        $em->persist($zipcode);
        $em->flush();
      }
      return $zipcode;
    }

    private function checkPlace($place_name, $zipcode) {
      $em = $this->getDoctrine()->getManager();
      $place = $em->getRepository('BaazaarLocationBundle:Place')->findOneBy(array('name' => $place_name));

      if(!$place){
        $place = new Place();
        $place->setName($place_name);
        $place->setZipcode($zipcode);

        $em->persist($place);
        $em->flush();
      }
      return $place;
    }
}
