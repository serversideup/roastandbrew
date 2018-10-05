<?php

namespace App\Utilities;

use DB;

use App\Models\Cafe;

class GoogleMaps{

  /*
    Geocodes an addres so we can get the latitude and longitude
  */
  public static function geocodeAddress( $address, $city, $state, $zip ){
    /*
      Builds the URL and request to the Google Maps API
    */
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode( $address.' '.$city.', '.$state.' '.$zip ).'&key='.env( 'GOOGLE_MAPS_KEY' );

    /*
			Creates a Guzzle Client to make the Google Maps request.
		*/
		$client = new \GuzzleHttp\Client();

		/*
			Send a GET request to the Google Maps API and get the body of the
			response.
		*/
		$geocodeResponse = $client->get( $url )->getBody();

		/*
			JSON decodes the response
		*/
		$geocodeData = json_decode( $geocodeResponse );

		/*
			Initializes the response for the GeoCode Location
		*/
		$coordinates['lat'] = null;
		$coordinates['lng'] = null;

		/*
			If the response is not empty (something returned),
			we extract the latitude and longitude from the
			data.
		*/
		if( !empty( $geocodeData )
        && $geocodeData->status != 'ZERO_RESULTS'
        && isset( $geocodeData->results )
        && isset( $geocodeData->results[0] ) ){
			$coordinates['lat'] = $geocodeData->results[0]->geometry->location->lat;
			$coordinates['lng'] = $geocodeData->results[0]->geometry->location->lng;
		}

		/*
			Return the found coordinates.
		*/
		return $coordinates;
  }

  /**
   * Finds the closest city to the latitude and longitude provided.
   *
   * @access public
   * @param float $latitude -> The latitude we are finding the closest to.
   * @param float $longitude -> The longitude we are finding the closest to.
   */
  public static function findClosestCity( $latitude, $longitude ){
    /*
      Find the closest city.
      From: https://gis.stackexchange.com/questions/31628/find-points-within-a-distance-using-mysql
    */
    $city = DB::select( DB::raw( 'SELECT id, radius, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance FROM cities ORDER BY distance LIMIT 1;') );

    /*
      Check if distance is in the radius. We don't want the closest city to be like
      500 miles away.
    */
    if( isset( $city[0] ) && $city[0]->distance < $city[0]->radius ){
      return $city[0]->id;
    }else{
      /*
        Return null if the closest city is too far.
      */
      return null;
    }
  }

  /**
   * Finds the cafes that belong in the city.
   *
   * @access public
   * @param integer $cityID -> The ID of the city we are finding the cafes for.
   * @param float   $latitude -> The latitude of the city we are finding the cafes for.
   * @param float   $longitude -> The longitude of the city we are finding the cafes for.
   * @param float   $radius -> The radius around the city we are finding the cafes for.
   */
  public static function findCafes( $cityID, $latitude, $longitude, $radius ){
    /*
      Finds the closest cafes to the city.
    */
    $cafes = DB::select( DB::raw( 'SELECT id, city_id, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance FROM cafes HAVING distance < '.$radius.' ORDER BY distance') );

    /*
      Iterate over the cafes and bind them to the new city.
    */
    foreach( $cafes as $cafe ){
      $cafeModel = Cafe::where('id', '=', $cafe->id )->first();

      /*
        If the city is null and within the radius, then we assign the cafe
        to the city. If the city is already set, we compare whether the new
        city is closer or not.
      */
      if( $cafeModel->city_id == null ){
        $cafeModel->city_id = $cityID;
        $cafeModel->save();
      }else{
        $otherCityDistance = DB::select( DB::raw( 'SELECT id, radius, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance FROM cities WHERE id = '.$cafe->city_id) );

        if( $otherCityDistance[0]->distance > $cafe->distance ){
          $cafeModel->city_id = $cityID;
          $cafeModel->save();
        }
      }
    }
  }
}
