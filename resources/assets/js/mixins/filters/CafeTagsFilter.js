export const CafeTagsFilter = {
  methods: {
    processCafeTagsFilter( cafe, tags ){
      /*
        If there are tags to be filtered, run the filter.
      */
      if( tags.length > 0 ){
        /*
          Makes array of the tags for the cafe
        */
        var cafeTags = [];

        /*
          Make array of cafe tags this is what we will check to
          see contains a filter.
        */
        for( var i = 0; i < cafe.tags.length; i++ ){
          cafeTags.push( cafe.tags[i].tag );
        }

        /*
          Iterate over all of the tags being filtered.
        */
        for( var i = 0; i < tags.length; i++ ){
          /*
            If the tag is in the array of cafe tags then
            we return true.
          */
          if( cafeTags.indexOf( tags[i] ) > -1 ){
            return true;
          }
        }

        /*
          If we made it this far, then we return false because
          the cafe doesn't contain the tags
        */
        return false;
      }else{
        return true;
      }
    }
  }
}
