export const CafeUserLikeFilter = {
  methods: {
    processCafeUserLikeFilter( cafe ){
      /*
        Checks to see if the cafe is liked by the user
      */
      if( cafe.user_like_count == 1 ){
        return true;
      }else{
        return false;
      }
    }
  }
}
