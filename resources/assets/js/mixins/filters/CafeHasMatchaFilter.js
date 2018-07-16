export const CafeHasMatchaFilter = {
  methods: {
    processCafeHasMatchaFilter( cafe ){
      /*
        Checks to see if the cafe has matcha
      */
      if( cafe.matcha == 1 ){
        return true;
      }else{
        return false;
      }
    }
  }
}
