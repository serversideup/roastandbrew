<?php
/*
  Defines the namespace of the service
*/
namespace App\Services;

/*
  Defines the models used by the service.
*/
use App\Models\Cafe;
use App\Models\Company;

/*
  Defines the utilities used by the service.
*/
use App\Utilities\GoogleMaps;

/*
  Uses sluggable
*/
use \Cviebrock\EloquentSluggable\Services\SlugService;

/*
  Defines the facades used by the service.
*/
use Auth;

/**
 * Defines the cafe service.
 */
class CafeService{
  /**
   * Adds a cafe to the database
   *
   * @param array $data Array of the data defining the new cafe.
   * @param int $addedBy Integer of the user adding the cafe.
   */
  public static function addCafe( $data, $addedBy ){
    /*
      Grabs the company ID.
    */
    $companyID = isset( $data['company_id'] ) ? $data['company_id'] : '';

    /*
      If the company exists, load the company. If the company
      does not exist, create a new company with what was
      sent from the user.
    */
    if( $companyID != '' ){
      $company = Company::where('id', '=', $companyID)->first();
    }else{
      $company = new Company();

      $company->name 				= $data['company_name'];
      $company->roaster			= $data['company_type'] == 'roaster' ? 1 : 0;
      $company->website 		= $data['website'];
      $company->logo 				= '';
      $company->description = '';
      $company->added_by 		= Auth::user()->id;

      $company->save();
    }

    /*
      Grab all of the new cafe data
    */
    $address 			= $data['address'];
    $city 				= $data['city'];
    $state 				= $data['state'];
    $zip 					= $data['zip'];
    $locationName = $data['location_name'];
    $brewMethods 	= $data['brew_methods'];

    $lat = isset( $data['lat'] ) ? $data['lat'] : 0;
    $lng = isset( $data['lng'] ) ? $data['lng'] : 0;

    if( $lat == 0 && $lng == 0 ){
      $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
      $lat = $coordinates['lat'];
      $lng = $coordinates['lng'];
    }

    /*
      Create a new cafe
    */
    $cafe = new Cafe();

    $cafe->company_id 			= $company->id;

    $cafe->slug 						= SlugService::createSlug(Cafe::class, 'slug', $company->name.' '.$locationName.' '.$address.' '.$city.' '.$state);
    $cafe->location_name 		= $locationName != null ? $locationName : '';
    $cafe->address 					= $address;
    $cafe->city 						= $city;
    $cafe->state 						= $state;
    $cafe->zip 							= $zip;
    $cafe->latitude 				= $lat;
    $cafe->longitude 				= $lng;
    $cafe->added_by 				= $addedBy;
    $cafe->tea 							= isset( $data['tea'] ) ? $data['tea'] : 0;
    $cafe->matcha 					= isset( $data['matcha'] ) ? $data['matcha'] : 0;
    $cafe->deleted 					= 0;

    $cafe->save();

    /*
      Attach the brew methods
    */
    $cafe->brewMethods()->sync( json_decode( $brewMethods ) );

    return $cafe;
  }

  /**
   * Saves edits to a cafe in the database.
   *
   * @param int $id ID of the cafe being edited.
   * @param array $data Array of the data defining the cafe updates.
   * @param bool $admin Determines if the updates are coming from the admin side.
   */
  public static function editCafe( $id, $data, $admin = false ){
    /*
      If the updates are not coming from the admin side, we update the
      company information.
    */
    if( !$admin ){
      /*
        If the company ID is not empty, load the company being
        edited.
      */
      if( isset( $data['company_id'] ) ){
        $company = Company::where( 'id', '=', $data['company_id'] )->first();

        /*
          If the request has a company name, update the company name.
        */
        if( isset( $data['company_name'] ) ){
          $company->name = $data['company_name'];
        }

        /*
          If the request has a company type, update the company type.
        */
        if( isset( $data['company_type'] ) ){
          $company->roaster = $data['company_type'] == 'roaster' ? 1 : 0;
        }

        /*
          If the request has a website, update the website.
        */
        if( isset( $data['website'] ) ){
          $company->website = $data['website'];
        }

        $company->logo 				= '';
        $company->description = '';

        /*
          Save the company
        */
        $company->save();
      }else{
        /*
          Create a new company
        */
        $company = new Company();

        /*
          If the data has a company name, add the company name.
        */
        if( isset( $data['company_name'] ) ){
          $company->name = $data['company_name'];
        }

        /*
          If the data has a company type, add the type but default to not a roaster.
        */
        if( isset( $data['company_type'] ) ){
          $company->roaster	= $data['company_type'] == 'roaster' ? 1 : 0;
        }else{
          $company->roaster = 0;
        }

        /*
          If the request has a website, add the company website.
        */
        if( isset( $data['website'] ) ){
          $company->website = $data['website'];
        }

        $company->logo 				= '';
        $company->description = '';
        $company->added_by 		= Auth::user()->id;

        /*
          Save the company.
        */
        $company->save();
      }
    }else{
      $company = Company::where( 'id', '=', $data['company_id'] )->first();
    }

    /*
      Grab the cafe we are updating.
    */
    $cafe = Cafe::where( 'id', '=', $id )->first();

    /*
      If the data has an address, update the address or
      using the existing address
    */
    if( isset( $data['address'] ) ){
      $address = $data['address'];
    }else{
      $address = $cafe->address;
    }

    /*
      If the data has an city, update the city or
      using the existing city
    */
    if( isset( $data['city'] ) ){
      $city = $data['city'];
    }else{
      $city = $cafe->city;
    }

    /*
      If the data has an city, update the city or
      using the existing city
    */
    if( isset( $data['state'] ) ){
      $state = $data['state'];
    }else{
      $state = $cafe->state;
    }

    /*
      If the data has an zip, update the zip or
      using the existing zip
    */
    if( isset( $data['zip'] ) ){
      $zip = $data['zip'];
    }else{
      $zip = $cafe->zip;
    }

    /*
      If the data has an location name, update the location name or
      using the existing location name
    */
    if( isset( $data['location_name'] ) ){
      $locationName = $data['location_name'];
    }else{
      $locationName = $cafe->location_name;
    }

    /*
      If the data has brew methods, set to the brew methods
      variable.
    */
    if( isset( $data['brew_methods'] ) ){
      $brewMethods 	= $data['brew_methods'];
    }

    /*
      If the request is not coming from the admin side,
      check to see if the lat and lng is set.
    */
    if( !$admin ){
      /*
        Grab the lat and lng from the data
      */
      $lat = isset( $data['lat'] ) != '' ? $data['lat'] : 0;
      $lng = isset( $data['lng'] ) != '' ? $data['lng'] : 0;

      /*
        If needed, update the latitude and longitude if not set.
      */
      if( $lat == 0 && $lng == 0 ){
        $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
        $lat = $coordinates['lat'];
        $lng = $coordinates['lng'];
      }
    }else{
      /*
        Determine if the address has changed at all. If it has,
        we need to re-geocode and update the slug.
      */
      if( ( isset( $data['address'] ) && $data['address'] != $cafe->address )
          || ( isset( $data['city'] ) && $data['city'] != $cafe->city )
          || ( isset( $data['state'] ) && $data['state'] != $cafe->state )
          || ( isset( $data['zip'] ) && $data['zip'] != $cafe->zip ) ){
            $coordinates = GoogleMaps::geocodeAddress( $data['address'], $data['city'], $data['state'], $data['zip'] );

            $slug = SlugService::createSlug(Cafe::class, 'slug', $company->name.' '.$locationName.' '.$address.' '.$city.' '.$state);

            $lat = $coordinates['lat'];
            $lng = $coordinates['lng'];
          }else{
            $slug = $cafe->slug;
            $lat = $cafe->lat;
            $lng = $cafe->lng;
          }
    }

    /*
      Update all of the cafe data to the new data.
    */
    $cafe->company_id 			= $company->id;
    $cafe->slug             = $slug;
    $cafe->location_name 		= $locationName != null ? $locationName : '';
    $cafe->address 					= $address;
    $cafe->city 						= $city;
    $cafe->state 						= $state;
    $cafe->zip 							= $zip;
    $cafe->latitude 				= $lat;
    $cafe->longitude 				= $lng;

    /*
      If the data has matcha, apply the matcha flag.
    */
    if( isset( $data['matcha'] ) ){
      $cafe->matcha = $data['matcha'];
    }

    /*
      If the data has tea, apply the tea flag.
    */
    if( isset( $data['tea'] ) ){
      $cafe->tea = $data['tea'];
    }

    /*
      If admin check for deleted.
    */
    if( $admin && isset( $data['deleted'] ) ){
      $cafe->deleted = $data['deleted'];
    }

    /*
      Save the cafe
    */
    $cafe->save();

    /*
      If the data has brew methods, sync the brew methods to what has
      been updated
    */
    if( isset( $data['brew_methods'] ) ){
      /*
        Attach the brew methods
      */
      $cafe->brewMethods()->sync( json_decode( $brewMethods ) );
    }

    /*
      Return the cafe
    */
    return $cafe;
  }
}
