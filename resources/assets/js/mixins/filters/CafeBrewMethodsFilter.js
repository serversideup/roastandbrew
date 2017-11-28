export const CafeBrewMethodsFilter = {
  methods: {
    processCafeBrewMethodsFilter( cafe, brewMethods ){
      /*
        If there are brew methods to be filtered, run the filter.
      */
      if( brewMethods.length > 0 ){
        /*
          Makes array of the brew methods for the cafe
        */
        var cafeBrewMethods = [];

        /*
          Makes array of brew methods tags so we can see if they
          are in the filter.
        */
        for( var i = 0; i < cafe.brew_methods.length; i++ ){
          cafeBrewMethods.push( cafe.brew_methods[i].method );
        }

        /*
          Iterate over all of the brew methods being filtered.
        */
        for( var i = 0; i < brewMethods.length; i++ ){
          /*
            If the tag is in the array of cafe tags then we return
            true.
          */
          if( cafeBrewMethods.indexOf( brewMethods[i] ) > -1 ){
            return true;
          }
        }

        /*
          If we made it this far, then we return false because the
          cafe doesn't contain the tags.
        */
        return false;

      }else{
        return true;
      }
    }
  }
}
