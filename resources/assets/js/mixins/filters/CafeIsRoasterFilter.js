export const CafeIsRoasterFilter = {
  methods: {
    processCafeIsRoasterFilter( cafe ){
      /*
        Checks to see if the cafe is a roaster or not
      */
      if( cafe.roaster == 1 ){
        return true;
      }else{
        return false;
      }
    }
  }
}
