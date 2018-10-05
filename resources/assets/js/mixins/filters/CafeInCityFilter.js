export const CafeInCityFilter = {
  methods: {
    processCafeInCityFilter( cafe, cityID ){
      /*
        Checks to see if the cafe has tea
      */
      if( cafe.city_id == cityID ){
        return true;
      }else{
        return false;
      }
    }
  }
}
