export const CafeTextFilter = {
  methods: {
    processCafeTextFilter( cafe, text ){
      /*
        Only process if the text is greater than 0
      */
      if( text.length > 0 ){
        /*
          If the name, location name, address, or city match the text that
          has been added, return true otherwise return false.
        */
        if( cafe.name.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )
          || cafe.location_name.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )
          || cafe.address.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )
          || cafe.city.toLowerCase().match( '[^,]*'+text.toLowerCase()+'[,$]*' )
        ){
          return true;
        }else{
          return false;
        }
      }else{
        return true;
      }
    }
  }
}
