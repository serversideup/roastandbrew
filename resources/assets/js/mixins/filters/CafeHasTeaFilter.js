export const CafeHasTeaFilter = {
  methods: {
    processCafeHasTeaFilter( cafe ){
      /*
        Checks to see if the cafe has tea
      */
      if( cafe.tea == 1 ){
        return true;
      }else{
        return false;
      }
    }
  }
}
