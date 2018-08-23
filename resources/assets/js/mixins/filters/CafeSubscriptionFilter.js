export const CafeSubscriptionFilter = {
  methods: {
    /*
      Determines if the cafe has a subscription or not.
    */
    processCafeSubscriptionFilter( cafe ){
      if( cafe.company.subscription == 1 ){
        return true;
      }else{
        return false;
      }
    }
  }
}
